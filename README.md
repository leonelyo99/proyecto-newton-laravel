# Proyecto

La base de datos es **proyecto**

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

**Obtener un usuario mediante id - GET**
- http://localhost/proyecto-2.0/backend/public/api/usuario/1

**Crear un usuario - POST**
- http://localhost/proyecto-2.0/backend/public/api/usuario/crear

- campos
- usuario -> string ->unico
- email -> string
- img -> archivo
- password -> password encriptada, como llega se guarda

**Modificar un usuario - POST**
- http://localhost/proyecto-2.0/backend/public/api/usuario/editar

**aclaracion**
- Si no queres actualizar usuario o el email no lo mandes porque choca la validacion

- campos
- id -> number
- usuario -> string
- email -> string
- img -> archivo
- password -> password encriptada, como llega se guarda

**Borrar un usuario - GET**
- http://localhost/proyecto-2.0/backend/public/api/usuario/borrar/8

**Borrar un usuario - GET**
- http://localhost/proyecto-2.0/backend/public/api/usuario/borrar/8

### Imagen

**Obtener imagen - GET**
- http://localhost/proyecto-2.0/backend/public/api/imagen/1562271925wp2358372.jpg

**aclaracion**
pone lo que te devuelve en el src de la imagen

### Empresa

**Crear una empresa - POST**
- http://localhost/proyecto-2.0/backend/public/api/empresa/crear

- campos
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

**Actualizar una empresa - POST**
- http://localhost/proyecto-2.0/backend/public/api/empresa/editar

**aclaracion**
- Si no queres actualizar documento no lo mandes porque choca la validacion

- campos
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

**Borrar una empresa - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/borrar/3

**Muestra todos los encargados de una empresa - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/encargados/1

**Todos los pedidos - GET**
- http://localhost/proyecto-2.0/backend/public/api/empresa/historial/1

### Encargado

** Crear un encargado - POST **
- http://localhost/proyecto-2.0/backend/public/api/encargado/crear

- campos
- empresa_id -> number
- nombre -> sring
- apellido -> sring
- usuario -> sring
- password -> sring
- img -> archivo

** Obtener un encargado - GET **
- http://localhost/proyecto-2.0/backend/public/api/encargado/1

** Borrar un encargado - GET **
- http://localhost/proyecto-2.0/backend/public/api/encargado/borrar/4

** Editar un encargado - POST **
- http://localhost/proyecto-2.0/backend/public/api/encargado/editar

**aclaracion**
- Si no queres actualizar un usuario no lo mandes porque choca la validacion

- campos
- id -> number
- nombre -> sring
- apellido -> sring
- usuario -> sring
- password -> sring
- img -> archivo

### Pedido
**crear un pedido - POST**
- http://localhost/proyecto-2.0/backend/public/api/pedido/crear

- campos
- tipo -> encargado, empresa
- creador_id -> number
- nombre -> string
- descripcion -> string
- progreso -> number
- precio -> number
- user_id -> number

**Editar un pedido - POST**
- http://localhost/proyecto-2.0/backend/public/api/pedido/crear

- campos
- id -> number
- nombre -> string
- descripcion -> string
- progreso -> number
- precio -> number

**Agregar imagen - POST**
- http://localhost/proyecto-2.0/backend/public/api/pedido/imagen

- campos
- pedido_id -> number
- imagen -> archivo

**Borrar un pedido - GET**
- http://localhost/proyecto-2.0/backend/public/api/pedido/borrar/5

### Login

**login - POST**
- http://localhost/proyecto-2.0/backend/public/api/login

- campos
- usuario -> string
- password -> string
- tipo -> string -> admite empresa, usuario o encargado




















