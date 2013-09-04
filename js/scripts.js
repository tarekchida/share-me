
function smWindowpop(url, width, height) {
    var leftPosition, topPosition; 
    leftPosition = (window.screen.width / 2) - ((width / 2) + 10); 
    topPosition = (window.screen.height / 2) - ((height / 2) + 50); 
    window.open(url, "Share_me", "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
    return false;
}
