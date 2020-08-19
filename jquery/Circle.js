
var el = document.getElementById('circle'); // get canvas
var options = {
    percent:  el.getAttribute('data-percent') || 25,
    size: el.getAttribute('data-size') || 220,
    lineWidth: el.getAttribute('data-line') || 9,
    rotate: el.getAttribute('data-rotate') || 0
}
var canvas = document.createElement('canvas');
var span = document.createElement('label');
span.setAttribute('class', 'circle_label_in');  
span.textContent = options.percent +'%';

    
if (typeof(G_vmlCanvasManager) !== 'undefined') {
    G_vmlCanvasManager.initElement(canvas);
}

var ctx = canvas.getContext('2d');
canvas.width = canvas.height = options.size;

el.appendChild(span);
el.appendChild(canvas);

ctx.translate(options.size / 2, options.size / 2); // change center
ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI); // rotate -90 deg

//imd = ctx.getImageData(0, 0, 240, 240);
var radius = (options.size - options.lineWidth) / 2.1; //cirgle grosse

var drawCircle = function(color, lineWidth, percent, lineHeight) {
		percent = Math.min(Math.max(0, percent || 0), 1);
		ctx.beginPath();
		ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
		ctx.strokeStyle = color;
        ctx.lineCap = 'round'; // butt, round or square
		ctx.lineWidth = lineWidth
		ctx.stroke();
};

drawCircle('#f5f5f5', options.lineWidth, 100 / 100);
drawCircle('#5f5fff', options.lineWidth, options.percent / 100); 

