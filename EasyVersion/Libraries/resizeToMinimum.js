function resizeToMinimum(){
  var minimum    = [640, 480];
  var current    = [window.outerWidth, window.outerHeight];
  var restricted = [];
  var i          = 2;

  while(i-- > 0){
    restricted[i] = minimum[i] > current[i] ? minimum[i] : current[i];
  }

  window.resizeTo(current[0], current[1]);
}

window.addEventListener('resize', resizeToMinimum, false)