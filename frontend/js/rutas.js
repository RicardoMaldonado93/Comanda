(function( window,document){
    libreria.getID('vista').enrutar()
            .ruta('/', 'vistas/inicio.html', null, null)
            .ruta('/menu', 'vistas/cliente/menu.html', null, null)
            .ruta('/login', 'vistas/login.html', null, null)
            .ruta('/admin', 'vistas/admin/index.html', null, null);

})(window, document);