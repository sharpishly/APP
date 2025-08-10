app = {};

app.progress = function(){
	
	app.bar = document.getElementById('progress-bar');

	var bar = document.createElement('div');
	
	bar.onclick = function(){
		
		bar.innerHTML = '100%';
		
	};
	
	bar.innerHTML = "25%";
	
	//TODO: role="progressbar" class="progress-bar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
	
	bar.setAttribute('style','border:1px solid #ddd; width:100%;background-color:#eee;padding:5px;text-align:center;border-radius:16px;');
	
	app.bar.appendChild(bar);
		
};

window.onload = function(){
	
	 app.progress();	
	
	 prettyBug(app);
};
