<?php

/*
 * This class manages the import for the Theme Options and the menus in the proper menu locations
 * added import for the Front and Blog static pages
 */

class wpGrade_import extends WPGrade_WP_Import {
	var $preStringOption;
	var $results;
	var $getOptions;
	var $saveOptions;
	var $termNames;

	function import_listing_categories( $xml_file, $option_file, $stepNumber = 1, $numberOfSteps = 1 ) {
		//do the actual import
		$this->import_stepped( $xml_file, $stepNumber, $numberOfSteps );

		//Ensure the $wp_rewrite global is loaded
		global $wp_rewrite;
		//Call flush_rules() as a method of the $wp_rewrite object
		$wp_rewrite->flush_rules();

		return true;
	}

	function import_posts_pages( $xml_file, $option_file, $stepNumber = 1, $numberOfSteps = 1 ) {
		//do the actual import
		$this->import_stepped( $xml_file, $stepNumber, $numberOfSteps );

		//only set the pages after the last step
		if ( $stepNumber == $numberOfSteps ) {

			if ( file_exists( $option_file ) ) {
				@include_once( $option_file );
			}

			//set the front and blog page
			// Use a static front page
			if ( isset( $demo_reading_pages['front'] ) && ! empty( $demo_reading_pages['front'] ) ) {
				update_option( 'show_on_front', 'page' );
				$home_page = get_page_by_title( $demo_reading_pages['front'] );
				if ( isset( $home_page->ID ) ) {
					update_option( 'page_on_front', $home_page->ID );
				}
			}

			// Set the blog page
			if ( isset( $demo_reading_pages['blog'] ) && ! empty( $demo_reading_pages['blog'] ) ) {
				$blog_page = get_page_by_title( $demo_reading_pages['blog'] );
				if ( isset( $blog_page->ID ) ) {
					update_option( 'page_for_posts', $blog_page->ID );
				}
			}

			update_option( 'noah_demo_data_imported', true );
			do_action('import_demo_data_end');
		}

		//Ensure the $wp_rewrite global is loaded
		global $wp_rewrite;
		//Call flush_rules() as a method of the $wp_rewrite object
		$wp_rewrite->flush_rules();

		return true;
	}

	/**
	 * The main controller for the actual import stage for pages, posts, etc
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	function import_stepped( $file, $stepNumber = 1, $numberOfSteps = 1 ) {
		add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
		add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

		$this->import_start( $file );

		$this->get_author_mapping();

		wp_suspend_cache_invalidation( true );

		$this->process_categories();
		$this->process_tags();
		$this->process_terms();

		//the processing of posts is stepped
		$this->process_posts_stepped( $stepNumber, $numberOfSteps );
		wp_suspend_cache_invalidation( false );

		//we do this only on the last step
		if ( $stepNumber == $numberOfSteps ) {
			//we process the menus because there are problems when the pages, posts, etc that don't first exist
			$this->process_menus();
		}

		// update incorrect/missing information in the DB
		$this->backfill_parents();
		$this->backfill_attachment_urls();
		$this->remap_featured_images();

		$this->import_end();
	}

	/**
	 * Create new posts based on import information
	 * Posts marked as having a parent which doesn't exist will become top level items.
	 * Doesn't create a new post if: the post type doesn't exist, the given post ID
	 * is already noted as imported or a post with the same title and date already exists.
	 * Note that new/updated terms, comments and meta are imported for the last of the above.
	 */
	function process_posts_stepped( $stepNumber = 1, $numberOfSteps = 1 ) {
		$this->posts = apply_filters( 'wp_import_posts', $this->posts );

		//get the total number of posts (actual posts, pages, custom posts, menus, etc)
		$numberOfPosts = count( $this->posts );

		//calculate the offset and the length for the current step
		$stepLength = (int) ( $numberOfPosts / $numberOfSteps );
		$length     = ( $stepNumber - 1 ) * $stepLength + $stepLength;

		//for the last step we take all that remained
		if ( $stepNumber == $numberOfSteps ) {
			$length = 99999;
		}

		//get only the posts for the current step
		$currentPosts = array_slice( $this->posts, 0, $length );

		foreach ( $currentPosts as $post ) {
			$post = apply_filters( 'wp_import_post_data_raw', $post );

			if ( ! post_type_exists( $post['post_type'] ) ) {
				printf( __( 'Failed to import "%s": Invalid post type %s', 'noah' ), esc_html( $post['post_title'] ), esc_html( $post['post_type'] ) );
				echo '<br />';
				do_action( 'wp_import_post_exists', $post );
				continue;
			}

			if ( isset( $this->processed_posts[ $post['post_id'] ] ) && ! empty( $post['post_id'] ) ) {
				continue;
			}

			if ( $post['status'] == 'auto-draft' || $post['status'] == 'draft' ) {
				continue;
			}

			if ( 'nav_menu_item' == $post['post_type'] ) {
				//we will add the menus at the end of the last step
				//$this->process_menu_item( $post );
				continue;
			}

			$post_type_object = get_post_type_object( $post['post_type'] );

			$post_exists = post_exists( $post['post_title'] );
			if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
				//printf( __('%s &#8220;%s&#8221; already exists.', 'noah'), $post_type_object->labels->singular_name, esc_html($post['post_title']) );
				//echo '<br />';

				//save it for later check if it exists - it may be unattached to it's parent
				$post_parent = (int) $post['post_parent'];
				if ( $post_parent ) {
					// if we already know the parent, map it to the new local ID
					if ( isset( $this->processed_posts[ $post_parent ] ) ) {
						$post_parent = $this->processed_posts[ $post_parent ];
						// otherwise record the parent for later
					} else {
						$this->post_orphans[ intval( $post['post_id'] ) ] = $post_parent;
						$post_parent                                      = 0;
					}
				}

				$comment_post_ID = $post_id = $post_exists;
			} else {
				$post_parent = (int) $post['post_parent'];
				if ( $post_parent ) {
					// if we already know the parent, map it to the new local ID
					if ( isset( $this->processed_posts[ $post_parent ] ) ) {
						$post_parent = $this->processed_posts[ $post_parent ];
						// otherwise record the parent for later
					} else {
						$this->post_orphans[ intval( $post['post_id'] ) ] = $post_parent;
						$post_parent                                      = 0;
					}
				}

				// map the post author
				$author = sanitize_user( $post['post_author'], true );
				if ( isset( $this->author_mapping[ $author ] ) ) {
					$author = $this->author_mapping[ $author ];
				} else {
					$author = (int) get_current_user_id();
				}

				$postdata = array(
					'import_id'      => $post['post_id'],
					'post_author'    => $author,
					'post_date'      => $post['post_date'],
					'post_date_gmt'  => $post['post_date_gmt'],
					'post_content'   => $post['post_content'],
					'post_excerpt'   => $post['post_excerpt'],
					'post_title'     => $post['post_title'],
					'post_status'    => $post['status'],
					'post_name'      => $post['post_name'],
					'comment_status' => $post['comment_status'],
					'ping_status'    => $post['ping_status'],
					'guid'           => $post['guid'],
					'post_parent'    => $post_parent,
					'menu_order'     => $post['menu_order'],
					'post_type'      => $post['post_type'],
					'post_password'  => $post['post_password']
				);

				$original_post_ID = $post['post_id'];
				$postdata         = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

				if ( 'attachment' == $postdata['post_type'] ) {
					$remote_url = ! empty( $post['attachment_url'] ) ? $post['attachment_url'] : $post['guid'];

					// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
					// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
					$postdata['upload_date'] = $post['post_date'];
					if ( isset( $post['postmeta'] ) ) {
						foreach ( $post['postmeta'] as $meta ) {
							if ( $meta['key'] == '_wp_attached_file' ) {
								if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) ) {
									$postdata['upload_date'] = $matches[0];
								}
								break;
							}
						}
					}

					$comment_post_ID = $post_id = $this->process_attachment( $postdata, $remote_url );
				} else {
					$comment_post_ID = $post_id = wp_insert_post( $postdata, true );
					do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );
				}

				if ( is_wp_error( $post_id ) ) {
					printf( __( 'Failed to import %s "%s"', 'noah' ), $post_type_object->labels->singular_name, esc_html( $post['post_title'] ) );
					if ( defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
						echo ': ' . $post_id->get_error_message();
					}
					echo '<br />';
					continue;
				}

				if ( $post['is_sticky'] == 1 ) {
					stick_post( $post_id );
				}
			}

			// map pre-import ID to local ID
			$this->processed_posts[ intval( $post['post_id'] ) ] = (int) $post_id;

			if ( ! isset( $post['terms'] ) ) {
				$post['terms'] = array();
			}

			$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

			// add categories, tags and other terms
			if ( ! empty( $post['terms'] ) ) {
				$terms_to_set = array();
				foreach ( $post['terms'] as $term ) {
					// back compat with WXR 1.0 map 'tag' to 'post_tag'
					$taxonomy    = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
					$term_exists = term_exists( $term['slug'], $taxonomy );
					$term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
					if ( ! $term_id ) {
						$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
						if ( ! is_wp_error( $t ) ) {
							$term_id = $t['term_id'];
							do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
						} else {
							printf( __( 'Failed to import %s %s', 'noah' ), esc_html( $taxonomy ), esc_html( $term['name'] ) );
							if ( defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
								echo ': ' . $t->get_error_message();
							}
							echo '<br />';
							do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
							continue;
						}
					}
					$terms_to_set[ $taxonomy ][] = intval( $term_id );
				}

				foreach ( $terms_to_set as $tax => $ids ) {
					$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
					do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
				}
				unset( $post['terms'], $terms_to_set );
			}

			if ( ! isset( $post['comments'] ) ) {
				$post['comments'] = array();
			}

			$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

			// add/update comments
			if ( ! empty( $post['comments'] ) ) {
				$num_comments      = 0;
				$inserted_comments = array();
				foreach ( $post['comments'] as $comment ) {
					$comment_id                                         = $comment['comment_id'];
					$newcomments[ $comment_id ]['comment_post_ID']      = $comment_post_ID;
					$newcomments[ $comment_id ]['comment_author']       = $comment['comment_author'];
					$newcomments[ $comment_id ]['comment_author_email'] = $comment['comment_author_email'];
					$newcomments[ $comment_id ]['comment_author_IP']    = $comment['comment_author_IP'];
					$newcomments[ $comment_id ]['comment_author_url']   = $comment['comment_author_url'];
					$newcomments[ $comment_id ]['comment_date']         = $comment['comment_date'];
					$newcomments[ $comment_id ]['comment_date_gmt']     = $comment['comment_date_gmt'];
					$newcomments[ $comment_id ]['comment_content']      = $comment['comment_content'];
					$newcomments[ $comment_id ]['comment_approved']     = $comment['comment_approved'];
					$newcomments[ $comment_id ]['comment_type']         = $comment['comment_type'];
					$newcomments[ $comment_id ]['comment_parent']       = $comment['comment_parent'];
					$newcomments[ $comment_id ]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
					if ( isset( $this->processed_authors[ $comment['comment_user_id'] ] ) ) {
						$newcomments[ $comment_id ]['user_id'] = $this->processed_authors[ $comment['comment_user_id'] ];
					}
				}
				ksort( $newcomments );

				foreach ( $newcomments as $key => $comment ) {
					// if this is a new post we can skip the comment_exists() check
					if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
						if ( isset( $inserted_comments[ $comment['comment_parent'] ] ) ) {
							$comment['comment_parent'] = $inserted_comments[ $comment['comment_parent'] ];
						}
						$comment                   = wp_filter_comment( $comment );
						$inserted_comments[ $key ] = wp_insert_comment( $comment );
						do_action( 'wp_import_insert_comment', $inserted_comments[ $key ], $comment, $comment_post_ID, $post );

						foreach ( $comment['commentmeta'] as $meta ) {
							$value = maybe_unserialize( $meta['value'] );
							add_comment_meta( $inserted_comments[ $key ], $meta['key'], $value );
						}

						$num_comments ++;
					}
				}
				unset( $newcomments, $inserted_comments, $post['comments'] );
			}

			if ( ! isset( $post['postmeta'] ) ) {
				$post['postmeta'] = array();
			}

			$post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );

			// add/update post meta
			if ( ! empty( $post['postmeta'] ) ) {
				foreach ( $post['postmeta'] as $meta ) {
					$key   = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
					$value = false;

					if ( '_edit_last' == $key ) {
						if ( isset( $this->processed_authors[ intval( $meta['value'] ) ] ) ) {
							$value = $this->processed_authors[ intval( $meta['value'] ) ];
						} else {
							$key = false;
						}
					}

					if ( $key ) {
						// export gets meta straight from the DB so could have a serialized string
						if ( ! $value ) {
							$value = maybe_unserialize( $meta['value'] );
						}

						add_post_meta( $post_id, $key, $value );
						do_action( 'import_post_meta', $post_id, $key, $value );

						// if the post has a featured-classic image, take note of this in case of remap
						if ( '_thumbnail_id' == $key ) {
							$this->featured_images[ $post_id ] = (int) $value;
						}
					}
				}
			}
		}
		unset( $currentPosts );
	}

	/**
	 * Performs post-import cleanup of files and the cache
	 */
	function import_end() {
		unset( $this->posts );
		wp_import_cleanup( $this->id );

		wp_cache_flush();
		foreach ( get_taxonomies() as $tax ) {
			delete_option( "{$tax}_children" );
			_get_term_hierarchy( $tax );
		}

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );

		//echo '<p>' . __( 'All done.', 'noah' ) . ' <a href="' . admin_url() . '">' . __( 'Have fun!', 'noah' ) . '</a>' . '</p>';
		//echo '<p>' . __( 'Remember to update the passwords and roles of imported users.', 'noah' ) . '</p>';

		do_action( 'import_end' );
	}

	/**
	 * Create new menu items based on import information
	 * Posts marked as having a parent which doesn't exist will become top level items.
	 * Doesn't create a new post if: the post type doesn't exist, the given post ID
	 * is already noted as imported or a post with the same title and date already exists.
	 * Note that new/updated terms, comments and meta are imported for the last of the above.
	 */
	function process_menus() {
		$this->posts = apply_filters( 'wp_import_posts', $this->posts );
		foreach ( $this->posts as $post ) {
			$post = apply_filters( 'wp_import_post_data_raw', $post );

			//			if ( ! post_type_exists( $post['post_type'] ) ) {
			//				printf( __( 'Failed to import &#8220;%s&#8221;: Invalid post type %s', 'noah' ),
			//					esc_html($post['post_title']), esc_html($post['post_type']) );
			//				echo '<br />';
			//				do_action( 'wp_import_post_exists', $post );
			//				continue;
			//			}

			if ( isset( $this->processed_posts[ $post['post_id'] ] ) && ! empty( $post['post_id'] ) ) {
				continue;
			}

			if ( $post['status'] == 'auto-draft' ) {
				continue;
			}

			if ( 'nav_menu_item' == $post['post_type'] ) {
				$this->process_menu_item( $post );
			}
		}
	}

	function import_theme_options( $option_file ) {
		if ( file_exists( $option_file ) ) {
			@include_once( $option_file );
		}

		if ( ! isset( $theme_options ) ) {
			return false;
		}

		if ( ! empty( $theme_options ) ) {
			$imported_options = json_decode( htmlspecialchars_decode( base64_decode( $theme_options ) ), true );

			/**
			 * $imported_options should be an array of options which should be replaced on import
			 */
			if ( ! empty ( $imported_options ) ) {
				foreach ( $imported_options as $key => $data ) {
					update_option( $key, maybe_unserialize( $data ) );
				}
			}
		}

		//Ensure the $wp_rewrite global is loaded
		global $wp_rewrite;
		//Call flush_rules() as a method of the $wp_rewrite object
		$wp_rewrite->flush_rules();

		return true;
	}

	function set_menus( $option_file ) {
		//get all registered menu locations
		$locations = get_theme_mod( 'nav_menu_locations' );

		//get all created menus
		$wpGrade_menus = wp_get_nav_menus();

		if ( file_exists( $option_file ) ) {
			@include_once( $option_file );
		}

		if ( ! isset( $demo_menus ) ) {
			return false;
		}

		//get the configuration
		$menu_conf = $demo_menus;

		if ( ! empty( $wpGrade_menus ) && ! empty( $menu_conf ) ) {
			foreach ( $wpGrade_menus as $wpGrade_menu ) {
				//check if we got a menu that corresponds to the Menu name array ($wpGrade_config->get('nav_menus')) we have set in menus.php
				if ( is_object( $wpGrade_menu ) && in_array( $wpGrade_menu->name, $menu_conf ) ) {
					$key = array_search( $wpGrade_menu->name, $menu_conf );

					if ( $key !== false ) {
						//if we have found a menu with the correct menu name apply the id to the menu location
						$locations[ $key ] = $wpGrade_menu->term_id;
					}
				}
			}
		}
		//update the theme with the new menus in the right location
		set_theme_mod( 'nav_menu_locations', $locations );

		return true;
	}

	/**
	 * Parse JSON import file and load data - pass to import
	 */
	function import_widget_data( $option_file ) {
		if ( file_exists( $option_file ) ) {
			@include_once( $option_file );
		}

		if ( empty( $demo_widgets ) ) {
			return false;
		}

		//first let's remove all the widgets in the sidebars to avoid a big mess
		$sidebars_widgets = wp_get_sidebars_widgets();
		foreach ( $sidebars_widgets as $sidebarID => $widgets ) {
			if ( $sidebarID != 'wp_inactive_widgets' ) {
				$sidebars_widgets[ $sidebarID ] = array();
			}
		}
		wp_set_sidebars_widgets( $sidebars_widgets );
		//let's get to work
		$json_data    = json_decode( base64_decode( $demo_widgets ), true );
		$sidebar_data = $json_data[0];
		$widget_data  = $json_data[1];

		foreach ( $sidebar_data as $title => $sidebar ) {
			$count = count( $sidebar );
			for ( $i = 0; $i < $count; $i ++ ) {
				$widget               = array();
				$widget['type']       = trim( substr( $sidebar[ $i ], 0, strrpos( $sidebar[ $i ], '-' ) ) );
				$widget['type-index'] = trim( substr( $sidebar[ $i ], strrpos( $sidebar[ $i ], '-' ) + 1 ) );
				if ( ! isset( $widget_data[ $widget['type'] ][ $widget['type-index'] ] ) ) {
					unset( $sidebar_data[ $title ][ $i ] );
				}
			}
			$sidebar_data[ $title ] = array_values( $sidebar_data[ $title ] );
		}

		$sidebar_data = array( array_filter( $sidebar_data ), $widget_data );
		if ( ! self::parse_import_data( $sidebar_data ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Import widgets
	 */
	public static function parse_import_data( $import_array ) {
		$sidebars_data = $import_array[0];
		$widget_data   = $import_array[1];

		$current_sidebars = get_option( 'sidebars_widgets' );
		$new_widgets      = array();

		foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :
			$current_sidebars[ $import_sidebar ] = array();
			foreach ( $import_widgets as $import_widget ) :

				//if the sidebar exists
				//if ( isset( $current_sidebars[$import_sidebar] ) ) :
				$title               = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
				$index               = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
				$current_widget_data = get_option( 'widget_' . $title );
				$new_widget_name     = self::get_new_widget_name( $title, $index );
				$new_index           = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

				if ( ! empty( $new_widgets[ $title ] ) && is_array( $new_widgets[ $title ] ) ) {
					while ( array_key_exists( $new_index, $new_widgets[ $title ] ) ) {
						$new_index ++;
					}
				}
				$current_sidebars[ $import_sidebar ][] = $title . '-' . $new_index;
				if ( array_key_exists( $title, $new_widgets ) ) {
					$new_widgets[ $title ][ $new_index ] = $widget_data[ $title ][ $index ];
					if ( ! empty( $new_widgets[ $title ]['_multiwidget'] ) ) {
						$multiwidget = $new_widgets[ $title ]['_multiwidget'];
						unset( $new_widgets[ $title ]['_multiwidget'] );
						$new_widgets[ $title ]['_multiwidget'] = $multiwidget;
					} else {
						$new_widgets[ $title ]['_multiwidget'] = null;
					}
				} else {
					$current_widget_data[ $new_index ] = $widget_data[ $title ][ $index ];
					if ( ! empty( $current_widget_data['_multiwidget'] ) ) {
						$current_multiwidget = $current_widget_data['_multiwidget'];
						$new_multiwidget     = $widget_data[ $title ]['_multiwidget'];
						$multiwidget         = ( $current_multiwidget != $new_multiwidget ) ? $current_multiwidget : 1;
						unset( $current_widget_data['_multiwidget'] );
						$current_widget_data['_multiwidget'] = $multiwidget;
					} else {
						$current_widget_data['_multiwidget'] = null;
					}
					$new_widgets[ $title ] = $current_widget_data;
				}

				//endif;
			endforeach;
		endforeach;

		if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
			update_option( 'sidebars_widgets', $current_sidebars );

			foreach ( $new_widgets as $title => $content ) {
				update_option( 'widget_' . $title, $content );
			}

			return true;
		}

		return false;
	}

	public static function get_new_widget_name( $widget_name, $widget_index ) {
		$current_sidebars = get_option( 'sidebars_widgets' );
		$all_widget_array = array();
		foreach ( $current_sidebars as $sidebar => $widgets ) {
			if ( ! empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
				foreach ( $widgets as $widget ) {
					$all_widget_array[] = $widget;
				}
			}
		}
		while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
			$widget_index ++;
		}
		$new_widget_name = $widget_name . '-' . $widget_index;

		return $new_widget_name;
	}
}
