(function( window,document){
    libreria.getID('vista').enrutar()
            .ruta('/', 'vistas/inicio.html', null, null)
            .ruta('/menu', 'vista/cliente/menu.html', null, null)
})(window, document);