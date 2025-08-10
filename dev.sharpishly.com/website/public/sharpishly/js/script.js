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
  'Home': {'url':app.url('sharpishly/index')},
//   'Community': {'url':app.url('sharpishly/portal/0/10')},
//   'Businesses': {'url':app.url('sharpishly/index/0/10')},
//   'Trade Only': {'url':app.url('sharpishly/tradeonly/0/10')},
//   'Tax Rebate': {'url':app.url('sharpishly/taxrebate/0/10')},
//   'Business In A Box': {'url':app.url('sharpishly/businessinabox/0/10')},
//   'Debt Managment': {'url':app.url('sharpishly/debtmanagement/0/10')},
//   'Customer Care': {'url':app.url('sharpishly/customercare/0/10')},
//   'Collective Bargaining': {'url':app.url('sharpishly/collectivebargaining/0/10')},
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
        'div':'Sharpishly',
        'b':'2025',
        // 'p':'Lorem Ipsum baby...',
        // 'p':'test...'
    };

    //@TODO: Create array for setting tag attributes

    attributes = {
        'style':'border:1px dashed blue;',
        'class':'foo'
    };

   app.process(obj,fields,attributes);
};

app.process = function(obj,fields,attributes){

    for(field in fields){

        //@TODO: The logic for setting id is incorrect
        attributes['id'] = fields[field];

        debug = {
            'fields':fields,
            'field':field,
            'attributes':attributes
        };

        // prettyBug(debug);

        app.setSection(obj,field,fields[field],attributes);

    } 

};

app.section = function(id){

    section = document.createElement('div');

    section.setAttribute('id',id);

    section.setAttribute('class','layout-item');

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

        // prettyBug(item);

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





