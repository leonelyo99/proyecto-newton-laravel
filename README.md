# Proyecto

- La base de datos es **proyecto**
- Desde la carpeta que contiene este archivo ejecuta **php artisan migrate**
- Me tenes que decir en que sifras la pasword y para logear tambien tenes que mandar la password cifrada creo que es con bcript
- la mayoria de las rutas tienen middleware estos middleware reciven el token que te manda el login, mandalo en el header de la peticion con el nombre token

## Configuracion

En el archivo **.env**

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=proyecto
        DB_USERNAME=root
        DB_PASSWORD=

## Enlaces

#### Si al final del enlace hay un numero es un id

### Usuarios

**Obtener todos los usuarios - GET**
- http://localhost/proyecto-2.0/backend/public/api/usuarios

*middleware*
solo usuarios

**Obtener un usuario mediante id - GET**
- http://localhost/proyecto-2.0/backend/public/api/usuario/1

*middleware*
solo usuarios

**Crear un usuario - POST**
- http://localhost/proyecto-2.0/backend/public/api/usuario/crear

*campos*
- usuario -> string ->unico
- email -> string
- img -> archivo
- password -> password encriptada, como llega se guarda

**Modificar un usuario - POST**
- http://localhost/proyecto-2.0/backend/public/api/usuario/editar

*middleware*
solo usuarios

**aclaracion**
- Si no queres actualizar usuario o el email no lo mandes porque choca la validacion

*campos*
- id -> number
- usuario -> string
- email -> string
- img -> archivo
- password -> password encriptada, como llega se guarda

**Borrar un usuario - GET**
- http://localhost/proyecto-2.0/backend/public/api/usuario/borrar/8

*middleware*
solo usuarios

### Imagen

**Obtener imagen - GET**
- http://localhost/proyecto-2.0/backend/public/api/imagen/1562271925wp2358372.jpg

**aclaracion**
pone lo que te devuelve este link en el src del img del html

### Empresa

**Crear una empresa - POST**
- http://localhost/proyecto-2.0/backend/public/api/empresa/crear

*campos*
- nombre -> string
- apellido -> string
- documento -> number ->unico
- nombreEmpresa -> string
- password -> string
- ubicacion -> string
- provincia -> string
- pais -> string
- img -> archivo

**Obtener una empresa mediante id - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/1

*middleware*
solo empresas

**Actualizar una empresa - POST**
- http://localhost/proyecto-2.0/backend/public/api/empresa/editar

*middleware*
solo empresas

**aclaracion**
- Si no queres actualizar documento no lo mandes porque choca la validacion

*campos*
- id -> number
- nombre -> string
- apellido -> string
- documento -> number ->unico
- nombreEmpresa -> string
- password -> string
- ubicacion -> string
- provincia -> string
- pais -> string
- img -> archivo

**Borrar una empresa - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/borrar/3

*middleware*
solo empresas

**Muestra todos los encargados de una empresa - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/encargados/1

*middleware*
solo empresas

**Todos los pedidos - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/historial/1

*middleware*
solo empresas

### Encargado

**Crear un encargado - POST**
- http://localhost/proyecto-2.0/backend/public/api/encargado/crear

*middleware*
solo empresas

*campos*
- empresa_id -> number
- nombre -> sring
- apellido -> sring
- usuario -> sring, unico
- password -> sring
- img -> archivo

**Obtener un encargado - GET**
- http://localhost/proyecto-2.0/backend/public/api/encargado/1

*middleware*
solo empresas y encargados

**Borrar un encargado - GET**
- http://localhost/proyecto-2.0/backend/public/api/encargado/borrar/4

*middleware*
solo empresas y encargados

**Editar un encargado - POST**
- http://localhost/proyecto-2.0/backend/public/api/encargado/editar

*middleware*
solo empresas y encargados

**aclaracion**
- Si no queres actualizar un usuario no lo mandes porque choca la validacion

*campos*
- id -> number
- nombre -> sring
- apellido -> sring
- usuario -> sring
- password -> sring
- img -> archivo

### Pedido
**crear un pedido - POST**
- http://localhost/proyecto-2.0/backend/public/api/pedido/crear

*middleware*
solo empresas y encargados

*campos*
- tipo -> encargado, empresa
- creador_id -> number
- nombre -> string
- descripcion -> string
- progreso -> number
- precio -> number
- user_id -> number

**Editar un pedido - POST**
- http://localhost/proyecto-2.0/backend/public/api/pedido/editar

*middleware*
solo empresas y encargados

*campos*
- id -> number
- nombre -> string
- descripcion -> string
- progreso -> number
- precio -> number

**Agregar imagen - POST**
- http://localhost/proyecto-2.0/backend/public/api/pedido/imagen

*middleware*
solo empresas y encargados

*campos*
- pedido_id -> number
- imagen -> archivo

**Borrar un pedido - GET**
- http://localhost/proyecto-2.0/backend/public/api/pedido/borrar/5

*middleware*
solo empresas y encargados

### Login

**login - POST**
- http://localhost/proyecto-2.0/backend/public/api/login

*campos*
- usuario -> string
- password -> string
- tipo -> string -> admite empresa, usuario o encargado

**todos - GET**
- http://localhost/proyecto-2.0/backend/public/api/todo

### Restablecer Password

**password, manda mail - POST**
- http://localhost/proyecto-2.0/backend/public/api/restablecerMail

*campos*
- email -> string -> email de contacto
- usuario -> string -> esto acepta el: campo email o usuario en usuario, campo usuario en encargado y campo documento en empresa
- tipo -> string -> admite empresa, usuario o encargado

**password, restablecer contraseña - POST**
- http://localhost/proyecto-2.0/backend/public/api/restablecerCon

*campos*
- usuario -> string -> esto acepta el: campo email o usuario en usuario, campo usuario en encargado y campo documento en empresa
- codigo -> string
- contraseña-> string -> ya cifrado anteriormente
- tipo -> string -> admite empresa, usuario o encargado





























