app.url = function(url){

  return window.location.origin + '/' +  url;

};

app.toggle = function(){
    // Toggle Navigation Menu
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');

    navbarToggle.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
    });
};

app.menu = {
  'Home': {'url':app.url('dashboard/index')},
//   'Todo': {'url':app.url('todo/index/0/10')},
//   'Admin': {'url':app.url('home/admin')},
//   'Sharpishly': {'url':app.url('sharpishly/index/1')}
};

app.createMenu = function(){

    nav = document.getElementById('navbar-menu');
  
    for(field in app.menu){
  
      a = document.createElement('a');
  
      a.innerHTML = field;
  
      a.setAttribute('href',app.menu[field].url);
  
      a.setAttribute('class',"navbar-link");
  
      li = document.createElement('li');
  
      li.setAttribute('class',"navbar-item");
  
      li.appendChild(a);
  
      nav.appendChild(li);
    }
  };

  /* Header Section */

app.get = function(id){

    return item = document.getElementById(id);

};

app.header = function(){

    main = app.get('hero');

    about = app.section('about');

    app.about(about);
    
    main.appendChild(about);

};

app.about = function(obj){

    fields = {
        'h1':'Sharpishly',
        //'h2':'Apply for eligable grants and funding',
        //'p':'Lorem Ipsum baby...'
    };

    //@TODO: Create array for setting tag attributes

    attributes = {
        'style':'border:1px dashed blue; clear:both; cursor:pointer;',
    };

   app.process(obj,fields,attributes);
};

app.process = function(obj,fields,attributes){

    for(field in fields){

        attributes['id'] = fields[field];

        app.setSection(obj,field,fields[field],attributes);

    } 

};

app.section = function(id){

    section = document.createElement('section');

    section.setAttribute('id',id);

    section.setAttribute('class','section-' + id);

    return section;
};

app.setAttribute = function(){
    for(attr in attributes){

        item.setAttribute(attr,attributes[attr]);

    };
};

app.setSection = function(obj,tag,txt,attributes){

    item = document.createElement(tag);

    app.setAttribute(item,attributes);

    item.innerHTML = txt;

    item.onclick = function(a){

        item = {'id':this.getAttribute('id')};

        prettyBug(item);

    };

    obj.appendChild(item);
};


  app.run = function(){
    
    app.header();

    app.toggle();

    app.createMenu();

  };

  // Persist cart on page reload (optional)
  window.addEventListener('load', () => {

    app.run();

    // prettyBug(app);

  });





