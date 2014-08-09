function slide_run(){
	var stage = new Kinetic.Stage({
		container: 'login_container',
		width: 270,
		height: 25
	});
	var layer = new Kinetic.Layer();

	var box = new Kinetic.Rect({
		x: 0,
		y: -200,
		width: 70,
		height: 300,
		fill: 'rgba(255, 255, 255, 0)',
		strokeWidth: 4,
		radius: 70,
		draggable: true
	});

	// add cursor styling
	box.on('mouseover', function() {
		document.body.style.cursor = 'pointer';
	});
	box.on('mouseout', function() {
		document.body.style.cursor = 'default';
	});

	stage.on('mousedown', function(etv) {
		var a = etv.target;
		a.fill("rgba(255, 255, 255, 0.3)");
		stage.draw();
	});

	stage.on('touchstart', function(etv) {
		var a = etv.target;
		a.fill("rgba(255, 255, 255, 0.3)");
		stage.draw();
	});

	stage.on('click', function(etv) {
		if (box.attrs.x >= 100) {
		$("#forml").submit();
	}
	var a = etv.target;
		a.position({x: 0, y: -200});
		stage.draw();
	});

	stage.on('mouseup', function(etv) {
		if (box.attrs.x >= 100) {
		$("#forml").submit();
	}
	var a = etv.target;
		a.position({x: 0, y: -200});
		a.fill("rgba(255, 255, 255, 0)");
		stage.draw();
	});

	stage.on('tap', function(etv) {
		var a = etv.target;
		if (box.attrs.x >= 100) {
		$("#forml").submit();
	}
	a.position({x: 0, y: -200});
		stage.draw();
	});

	stage.on('touchend', function(etv) {
		var a = etv.target;
		if (box.attrs.x >= 100) {
			$("#forml").submit();
		}
		a.fill("rgba(255, 255, 255, 0)");
		a.position({x: 0, y: -200});
		stage.draw();
	});
	layer.add(box);
	stage.add(layer);
}