# FRONTECH
 SOLUCIÓN INTEGRAL BASADA EN AWS PARA LA GESTIÓN Y SEGUIMIENTO DE REPARACIONES

## 🌍 Descripción General

El sistema permite registrar, consultar y gestionar reparaciones de equipos, proporcionando un historial completo de intervenciones. Se accede mediante una aplicación web desarrollada en PHP, conectada a una base de datos en MySQL.

## 🧱 Arquitectura en AWS

El despliegue se ha realizado siguiendo buenas prácticas de seguridad, escalabilidad y alta disponibilidad:

- **VPC** con subredes públicas y privadas.
- **2 Instancias EC2 (Linux)** con Apache + PHP sirviendo la aplicación.
- **Balanceador de Carga (ELB)** distribuyendo el tráfico web entre los servidores.
- **Base de Datos RDS (MySQL)** alojada en subred privada.
- **Grupos de Seguridad e IAM** configurados con políticas adecuadas.

## 🔧 Funcionalidades Principales

- Inicio de sesión con control de acceso.
- Registro de nuevas reparaciones.
- Consulta del estado y seguimiento de intervenciones.
- Panel de control según rol (técnico, administrador o cliente).
- Conexión con la base de datos MySQL (RDS).

## ⚙️ Tecnologías Utilizadas

- **Amazon Web Services (AWS)**: EC2, RDS, VPC, ELB, etc.
- **PHP**: Lógica del lado servidor.
- **MySQL**: Base de datos en RDS.
- **Apache2**: Servidor web.
- **HTML/CSS**: Interfaz básica.

## 🚀 Desarrollo PHP

La aplicación PHP está compuesta por varios archivos organizados de forma sencilla, con conexión a base de datos MySQL (RDS) y estructura modular. A continuación, se explican los componentes clave:

constantes.php: Contiene los parámetros de conexión con la base de datos RDS (host, usuario, contraseña y nombre de la BD).

connect.php: Define funciones reutilizables para ejecutar consultas de forma segura y sencilla.

🔐 Autenticación

index.php: Redirige al archivo login.php.

login.php: Formulario y validación de acceso al sistema.

logoff.php: Cierra la sesión del usuario y redirige al login.

La autenticación está conectada a una tabla usuarios en la base de datos, que contiene campos como id, usuario, password y rol.

🧭 Vistas principales por rol

principal_admin.php: Panel principal para administradores. Acceso a usuarios, clientes e incidencias.

principal_tecnico.php: Vista de técnicos con asignaciones activas y pendientes.

principal_cliente.php: Panel del cliente con sus propias incidencias y su estado.

⚙️ Gestión del sistema

administrar_usuarios.php: Alta, baja y modificación de usuarios del sistema.

administrar_clientes.php: Gestión de clientes registrados.

administrar_incidencias.php: Visualización y modificación de incidencias del sistema.

📁 Vistas

/views/: Carpeta que contiene los fragmentos visuales (como cabecera, pie de página, estilos, etc.), reutilizados por los diferentes roles.

## 🌐 Esquema de la red

![image](https://github.com/user-attachments/assets/c2d61185-6200-4591-bbe4-23cdab2f9ff8)

## ⚡ Modelo entidad-relación

![image](https://github.com/user-attachments/assets/377b341f-b292-4ffa-96be-97ffb633c682)

![image](https://github.com/user-attachments/assets/5d2c8149-dce3-4a6a-b5c9-f268cf0c8a3c)

## 🚀 Despliegue

Clonar este repositorio:
- git clone https://github.com/GarekFronceda/FRONTECH.git
