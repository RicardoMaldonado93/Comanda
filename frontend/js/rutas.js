(function( window,document){
    libreria.getID("vista").enrutar()
            .ruta('/', 'vistas/inicio.html', null, null)
            .ruta('/menu', 'vistas/cliente/menu.html', null, null)
            .ruta('/login', 'vistas/login.html', null, null)
            .ruta('/admin', 'vistas/empleado/admin.html', null, null)
            .ruta('/bartender', 'vistas/empleado/bartender.html', null, null)
            .ruta('/cocinero', 'vistas/empleado/cocinero.html', null, null)
            .ruta('/mozo', 'vistas/empleado/mozo.html', null, null)


})(window, document);