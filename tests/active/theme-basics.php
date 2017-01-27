<?php
/**
 * Class BaseTest
 *
 * @package Empty
 */

/**
 * Sample test case.
 */
class BaseTest extends WP_UnitTestCase {
	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( function_exists('kunst_get_option') );
	}

	/**
	 * Ensure that we are working with the right theme
	 */
	function test_kunst_exists() {
		$theme = wp_get_theme();
		$this->assertEquals( 'kunst', $theme->get('Name') );
		$this->assertEquals( 'Pixelgrade', $theme->get('Author') );
		$this->assertEquals( 'https://pixelgrade.com', $theme->get('AuthorURI') );
		$this->assertEquals( 'https://pixelgrade.com/demos/kunst/', $theme->get('ThemeURI') );
		$this->assertEquals( 'kunst', $theme->get('TextDomain') );
	}

	/**
	 * A simple test case which should check that we don't miss assets
	 */
	function test_assets_exists() {
		// make sure you have the scripts
		$this->assertFalse( wp_script_is( 'masonry' ) );
		$this->assertFalse( wp_script_is( 'kunst-scripts' ) );

		// and the styles
		$this->assertFalse( wp_style_is( 'kunst-style' ) );
	}

	/**
	 * Scenario: A post has 2 categories assigned.
	 * Test if the `kunst_get_cats_list` function returns to correct markup
	 */
	function test_kunst_get_cats_list() {

		$term_id = self::factory()->category->create( array( 'slug' => 'woo', 'name' => 'WOO!' ) );
		$term_id2 = self::factory()->category->create( array( 'slug' => 'hoo', 'name' => 'HOO!' ) );
		// create a 3rd category, but don't assign it. this should cover that only the assigned categories are counted
		$term_id3 = self::factory()->category->create( array( 'slug' => 'hoohoo', 'name' => 'AAAAAHOO!' ) );

		$post_ID = self::factory()->post->create( array( 'post_type' => 'post', 'post_title' => 'You suck!' ) );

		wp_set_post_categories( $post_ID, array( $term_id, $term_id2 ) );

		$this->go_to( get_permalink( $post_ID ) );

		$kunst_cats = kunst_get_cats_list( $post_ID );

		$this->assetEquals( '<span class="cat-links"><a href="http://kunst.dev/?cat=3" rel="category">HOO!</a>, <a href="http://kunst.dev/?cat=2" rel="category">WOO!</a></span>', $kunst_cats);
	}
}
