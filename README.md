# 🧩 Minerva Lab VR — Sistema de Reservación de Equipos y Salones Virtuales

**Minerva Lab VR** es una plataforma web desarrollada en **Laravel** con **Livewire** y **Tailwind CSS**, compatible con **PostgreSQL**.  
Su propósito es **centralizar la gestión y reservación de equipos de realidad virtual y salones virtuales**, automatizar el proceso de registro y control de uso, y ofrecer un sistema eficiente para la administración de recursos tecnológicos en laboratorios académicos.

---

## 🎯 Objetivo general

Diseñar e implementar el sistema Minerva Lab VR para centralizar la gestión y reservación de equipos de realidad virtual y salones virtuales, facilitando el registro, control de uso, generación de reportes y acceso seguro para estudiantes, docentes y administradores.

---

## 🚀 Funcionalidades principales

-   🗓️ **Reservación de equipos VR:** registro y gestión de préstamos de gafas, accesorios y dispositivos de realidad virtual.
-   🏢 **Reservación de salones virtuales:** administración de espacios, horarios y disponibilidad para actividades académicas.
-   👥 **Gestión de usuarios:** roles definidos (administrador, docente, estudiante) con autenticación segura mediante **Laravel Web Auth**.
-   📊 **Reportes y métricas:** generación automática de reportes de uso, asistencia y disponibilidad en PDF.
-   🔔 **Notificaciones:** alertas automáticas por correo sobre reservas, recordatorios y confirmaciones.
-   🔐 **Panel administrativo:** control centralizado de usuarios, recursos y registros de reservación.

---

## 🧩 Secciones del sitio

-   🏠 **Inicio:** información general, recursos destacados y enlaces rápidos.
-   🗓️ **Reservaciones:** gestión de equipos y salones virtuales.
-   📊 **Reportes:** acceso a estadísticas y reportes de uso.

---

## 🛠️ Tecnologías empleadas

| Componente                | Herramienta                                |
| ------------------------- | ------------------------------------------ |
| **Lenguaje principal**    | PHP 8.3                                    |
| **Framework**             | Laravel 12                                 |
| **Interactividad**        | Livewire                                   |
| **Estilos**               | Tailwind CSS                               |
| **Base de datos**         | PostgreSQL / MySQL (compatibles)           |
| **Autenticación**         | Laravel Web (login, registro y roles RBAC) |
| **Entorno de desarrollo** | Visual Studio Code                         |
| **Gestión de tareas**     | Jira (metodología ágil SCRUM)              |

---

## 🧠 Metodología de desarrollo

El proyecto fue desarrollado bajo la **metodología ágil SCRUM**, utilizando sprints iterativos que permitieron la entrega continua de módulos funcionales, la validación con usuarios reales y la mejora progresiva del sistema.  
Cada iteración incluyó planificación, desarrollo, pruebas y revisión, asegurando calidad, trazabilidad y adaptabilidad a las necesidades del laboratorio y la comunidad académica.

---

## 📦 Requisitos de instalación

### 🔧 Requisitos previos

-   PHP >= 8.3
-   Composer
-   Node.js y NPM
-   PostgreSQL o MySQL
-   Extensiones de PHP (OpenSSL, PDO, Mbstring, Tokenizer, XML, JSON, Ctype, ZIP)

### ⚙️ Pasos de instalación

```bash
# Clonar el repositorio
git clone https://github.com/cristianpined4/MinervaLab.git

# Entrar al directorio del proyecto
cd MinervaLab

# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node
npm install && npm run dev

# Copiar y configurar el entorno
cp .env.example .env

# Editar el archivo .env con tus credenciales
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=minervalab
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_contraseña

# Generar clave de la aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate --seed

# Iniciar el servidor local
php artisan serve
```

---

## 🔐 Seguridad y cumplimiento

El sistema implementa **RBAC (Role-Based Access Control)** para la gestión de roles y permisos, asegurando trazabilidad y control de accesos.
Cumple con las normativas de:

-   **Ley de Acceso a la Información Pública (LAIP)**
-   **Ley de Protección de Datos Personales**
-   **Normas ISO/IEC 27001 y 25010**
-   **Pautas WCAG 2.1** para accesibilidad web
-   **Buenas prácticas OWASP Top 10** para seguridad en desarrollo Laravel.

---

## 💾 Infraestructura y alojamiento

El sistema puede alojarse en un **servidor institucional o dedicado**, con:

-   Certificado SSL (HTTPS)
-   Copias de seguridad automáticas
-   Panel de administración remoto
-   Disponibilidad 24/7 para consultas, reservaciones y descarga de documentos.

---

## 🤝 Contribuciones

Las contribuciones al proyecto son bienvenidas.
Realiza un _fork_, crea una rama con tus cambios y envía un _pull request_.

---

## 👥 Equipo de desarrollo

Proyecto desarrollado por estudiantes de **Ingeniería de Sistemas Informáticos** de la **Facultad Multidisciplinaria Oriental — Universidad de El Salvador**, como parte de la materia _Administración de Proyectos Informáticos_, bajo la asesoría del **Ing. César Misael Rodríguez Franco**.

### 👨‍💻 Colaboradores

-   **López Medrano, Gerardo Alexander** — LM20003
-   **Pineda Blanco, Cristian Alberto** — PB20002
-   **Viera Lazo, Edras Ariel** — VL20011
-   **Vásquez Vásquez, Andrés Isaí** — VV18009
-   **Álvarez Pérez, Carlos Vicente** — AP20007
-   **Santos Díaz, Eliseo Santos** — SD20007
-   **Bonilla Cortez, Oscar Alejandro** — BC18010
-   **Conde Salgado, Nelson Numan** — CS21027
-   **García Rivera, Billy Alexander** — GR20036
-   **Parada Barrero, Luis Andrés** — PB19022

---

## 🪪 Licencia

Este proyecto se distribuye bajo la licencia **MIT**.
Consulta el archivo [LICENSE](LICENSE) para más información.

---

## 🏛️ Institución

**Sección de Ingeniería de Sistemas Informáticos — FMO UES**
**Universidad de El Salvador**
📧 Contacto: [correo@ues.edu.sv](mailto:correo@ues.edu.sv)
📍 San Miguel Centro, San Miguel, El Salvador

---

## ⭐ Si este proyecto te fue útil o te inspiró, no olvides dejar una estrella en el repositorio.
