function bez(ctx,x0,y0,deg0,dist0,x1,y1,deg1,dist1){
  var cp0x=x0+dist0*Math.cos(deg0/180 * Math.PI);
  var cp0y=y0+dist0*Math.sin(deg0/180 * Math.PI);
  var cp1x=x1-dist1*Math.cos(deg1/180 * Math.PI);
  var cp1y=y1-dist1*Math.sin(deg1/ 180 * Math.PI);
  ctx.bezierCurveTo(cp0x, cp0y, cp1x,cp1y, x1,y1);
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function dbug(str) {
  if(str==''){
    ddiv.style.display = "none";
    }
  else{ddiv.style.display = "block";}
    ddiv.innerHTML = str;
    }
function dbuga(str) {
  ddiv.style.display = "block";
  ddiv.innerHTML += "<br />"+str;
  }
function GetOffset (object, offset) {
            if (!object)
                return;
            offset.x += object.offsetLeft;
            offset.y += object.offsetTop;

            GetOffset (object.offsetParent, offset);
        }
function GetScrolled (object, scrolled) {
            if (!object)
                return;
            scrolled.x += object.scrollLeft;
            scrolled.y += object.scrollTop;

    if (object.tagName.toLowerCase () != "html") {
        GetScrolled (object.parentNode, scrolled);
    }
  }
function urlDecode(str) {
  return decodeURIComponent((str+'').replace(/\+/g, '%20'));
  }
function solveAngle(a, b, c) {  // Returns angle C using law of cosines
  var temp = (a * a + b * b - c * c) / (2 * a * b);
  if (temp >= -1 && temp <= 1){return radToDeg(Math.acos(temp));}
  else{return "No solution";}
  }
function degToRad(x) {
	return x / 180 * Math.PI;
}

function radToDeg(x) {
	return x / Math.PI * 180;
}
function rnd(range) {
  return Math.floor(Math.random()*range);
  }
