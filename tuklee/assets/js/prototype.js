String.prototype.fulltrim = function() {
    return this.replace(/\s/g, '');
};

String.prototype.isFullEmpty = function(){
	return this.fulltrim() === "";
};