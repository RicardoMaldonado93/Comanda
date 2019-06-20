(function(window,document){
    'use strict';

    var inicio = function(){
        var elemento = null,
        marco = null,
        rutas ={},
        controladores= {},
        controlador,
        libreria = {
            getID: function(id){
                elemento = document.getElementById(id);
                return this;
            },

            noSubmit: function(){
                elemento.addEventListener('submit', function(e){
                    e.preventDefault();

                }, false);

                return this; 
  
            },

            enrutar: function(){
                marco = elemento;
                return this;

            },

            ruta: function(ruta, plantilla, controlador, carga){
                rutas[ruta] = {
                                    'plantilla': plantilla,
                                    'controlador': controlador,
                                    'carga': carga,
                              }
            } ,

            manejadorRutas: function(){

            }
        };
        return libreria;
    }
    if(typeof window.libreria === 'undefined'){
        window.libreria = window._ = inicio();
        window.addEventListener('hashchange', _.manejadorRutas, false);
    }
    else{
        console.log("se esta llamando la libreria nuevamente");
    }
})(window, document);

rutas = {
    '/': {
        'plantilla': 'vista/inicio.html',
        'controlador':'',
        'carga': null
    },

    'menu': {
        'plantilla': 'vistas/cliente/menu.html',
        'controlador' : 'menu',
        'carga': null
    }
}