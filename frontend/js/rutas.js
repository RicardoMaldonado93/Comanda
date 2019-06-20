(function( window,document){
    libreria.getID('vista').enrutar()
            .ruta('/', 'vistas/inicio.html', null, null)
            .ruta('/menu', 'vistas/cliente/menu.html', null, null);
            
})(window, document);