var Util = {
    isTouch: !! ( ( "ontouchstart" in window ) || window.DocumentTouch && document instanceof DocumentTouch )
};
