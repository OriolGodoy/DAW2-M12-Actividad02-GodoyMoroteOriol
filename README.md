# El Manantial - Reserva de Taules

## Descripción del Proyecto

**El Manantial - Reserva de Taules** es una aplicación web desarrollada para facilitar la gestión de las mesas y recursos de un restaurante. Esta aplicación, diseñada para los camareros, permite ver la disponibilidad de mesas en tiempo real, registrar ocupaciones y liberar mesas cuando los clientes finalizan su estadía. 

El objetivo es que el restaurante pueda gestionar el espacio de manera eficiente, con registro de ocupaciones y una interfaz intuitiva para el equipo de trabajo.

## Funcionalidades

- **Login de Usuarios**: Los camareros inician sesión en el sistema para registrar o liberar mesas. Los usuarios ya están registrados en la base de datos, por lo que no se requiere un sistema de alta/baja de usuarios.
- **Visualización de Salas**: Se muestran todas las áreas del restaurante (3 terrazas, 2 comedores, y 4 salas privadas), junto con la cantidad de mesas y sillas disponibles en cada una.
- **Ocupación de Mesas**: Cada camarero puede marcar las mesas como ocupadas o libres. El estado de cada mesa se actualiza en tiempo real para todos los usuarios.
- **Histórico de Ocupaciones**: Se registra el día y la hora de cada ocupación y liberación de mesa, permitiendo ver el historial de uso de cada recurso. Es posible filtrar por recurso (mesa) o ubicación (sala) para analizar el uso.

## Tecnologías Utilizadas

- **Frontend**: HTML, CSS, JavaScript (con SweetAlert para alertas personalizadas)
- **Backend**: PHP (procedural)
- **Base de Datos**: MySQL, para el almacenamiento de usuarios y registros de ocupaciones
- **Estilos**: Bootstrap 5 y CSS personalizado para un diseño homogéneo y responsive

## Requisitos del Proyecto

Este proyecto se desarrolla como parte de un ejercicio transversal para el curso de **Desenvolupament d'Aplicacions Web** y abarca los siguientes módulos:

- **MP2 - Bases de Dades**: Diseño y creación de la base de datos, consultas para gestionar las ocupaciones.
- **MP6 - Desenvolupament web en entorn client**: Validación de formularios y dinámicas con JavaScript y SweetAlert.
- **MP7 - Desenvolupament web en entorn servidor**: Manejo de sesiones, conexión y consulta a la base de datos en PHP.
- **MP8 - Desplegament d'Aplicacions Web**: Planificación con Gantt o GitHub Roadmap, control de versiones, y documentación en equipo.
- **MP9 - Disseny d'interfícies web**: Diseño de mockups, estilo uniforme y experiencia de usuario optimizada.

## Estructura del Restaurante

- **Salas**:
  - 3 Terrazas
  - 2 Comedores
  - 4 Salas Privadas
- **Distribución de Mesas**: Cada sala tiene una distribución definida por el equipo para maximizar el espacio y comodidad de los clientes.

## Organización del Proyecto

- **Repositorio GitHub**: Utilizamos un repositorio compartido donde cada miembro sube los cambios diariamente.
- **Control de Versiones**:
  - Ramas específicas para cada funcionalidad.
  - Issues y milestones para seguimiento de tareas.
  - Labels para organizar el estado de cada tarea.
- **Roles del Equipo**:
  - **Coordinador**: Encargado de la organización general del proyecto y el flujo de trabajo.
  - **Impulsor**: Facilita la resolución de problemas y motiva al equipo.
  - **Finalizador**: Responsable de revisar y cerrar las tareas finalizadas.
- **Daily Meeting**: Reunión diaria para revisar el progreso, asignar tareas y resolver dudas en equipo.
