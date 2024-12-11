CREATE DATABASE elmanantial;

USE elmanantial;

CREATE TABLE tbl_rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE tbl_usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    email_usuario VARCHAR(100) NOT NULL UNIQUE,
    password_usuario VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES tbl_rol(id_rol)
);

CREATE TABLE tbl_sala (
    id_sala INT PRIMARY KEY,
    nombre_sala VARCHAR(25) NOT NULL,
    tipo_sala ENUM('terraza', 'comedor', 'privada') NOT NULL,
    capacidad_total INT NOT NULL,
    imagen_sala VARCHAR(255) -- Ruta de la imagen asociada
);

CREATE TABLE tbl_mesa (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_sala INT NOT NULL,
    num_sillas_mesa INT NOT NULL,
    estado_mesa ENUM('libre', 'ocupada') NOT NULL DEFAULT 'libre',
    FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala)
);

CREATE TABLE tbl_reserva_recurso (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL, -- Relacionado con el usuario (ej. camarero)
    id_recurso INT, -- ID del recurso reservado (opcional, según necesidad)
    fecha_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario)
);

CREATE TABLE tbl_ocupacion (
    id_ocupacion INT AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT NOT NULL,
    id_usuario INT NOT NULL, 
    id_cliente INT NOT NULL,
    fecha_hora_ocupacion DATETIME NOT NULL,
    fecha_hora_desocupacion DATETIME,
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa),
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario)
);

INSERT INTO tbl_rol (nombre_rol) VALUES
('Administrador'),
('Gerente'),
('Camarero'),
('Mantenimiento'),
('Cocinero');

-- Ejemplo de usuarios iniciales
INSERT INTO tbl_usuario (nombre_usuario, email_usuario, password_usuario, id_rol) VALUES
('Iker Manrique', 'iker@example.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi', 2), -- qweQWE123
('Adrian Vazquez', 'adrian@example.com', '$2a$12$DB3.O4aga98EH./zW9P9beKfklJkTcXMY0AnL3T6nheQhpM3usreO', 5), -- asdASD456
('Admin User', 'admin@example.com', '$2a$12$b509yhiIiUsHDKfE8HdNnea.1OEVhd4ukrnc54axOg5TDuDE2MNgC', 1), -- zxcZXC789
('Mario Manzano', 'mario@example.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi', 3), -- qweQWE123
('Alan Capoue', 'alan@example.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi', 4); -- qweQWE123

-- Ejemplo de reservas iniciales
INSERT INTO tbl_reserva_recurso (id_usuario, fecha_reserva, hora_inicio, hora_fin) VALUES
(1, '2024-12-10', '12:00:00', '14:00:00'),
(2, '2024-12-10', '18:00:00', '20:00:00');

-- Inserciones para la tabla tbl_sala
INSERT INTO tbl_sala (id_sala, nombre_sala, tipo_sala, capacidad_total, imagen_sala) VALUES
(1, 'Terraza Principal', 'terraza', 50, 'img/terraza_principal.jpg'),
(2, 'Terraza Secundaria', 'terraza', 40, 'img/terraza_secundaria.jpg'),
(3, 'Comedor Principal', 'comedor', 80, 'img/comedor_principal.jpg'),
(4, 'Comedor Privado', 'privada', 20, 'img/comedor_privado.jpg'),
(5, 'Terraza VIP', 'terraza', 30, 'img/terraza_vip.jpg');
