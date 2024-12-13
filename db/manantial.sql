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
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(25) NOT NULL,
    tipo_sala ENUM('terraza', 'comedor', 'privada') NOT NULL,
    imagen_sala VARCHAR(255) 
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
    id_usuario INT NOT NULL, 
    id_recurso INT, 
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

INSERT INTO tbl_usuario (nombre_usuario, email_usuario, password_usuario, id_rol) VALUES
('Iker Manrique', 'iker@example.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi', 2), -- qweQWE123
('Adrian Vazquez', 'adrian@example.com', '$2a$12$DB3.O4aga98EH./zW9P9beKfklJkTcXMY0AnL3T6nheQhpM3usreO', 5), -- asdASD456
('Admin User', 'admin@example.com', '$2a$12$b509yhiIiUsHDKfE8HdNnea.1OEVhd4ukrnc54axOg5TDuDE2MNgC', 1), -- zxcZXC789
('Mario Manzano', 'mario@example.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi', 3), -- qweQWE123
('Alan Capoue', 'alan@example.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi', 4); -- qweQWE123

INSERT INTO tbl_reserva_recurso (id_usuario, fecha_reserva, hora_inicio, hora_fin) VALUES
(1, '2024-12-10', '12:00:00', '14:00:00'),
(2, '2024-12-10', '18:00:00', '20:00:00');

INSERT INTO tbl_sala (id_sala, nombre_sala, tipo_sala, imagen_sala) VALUES
(1, 'Terraza Principal', 'terraza','../img/terraza1.jpeg'),
(2, 'Terraza Secundaria', 'terraza','../img/terraza2.jpeg'),
(3, 'Comedor Principal', 'comedor','../img/comedor1.jpeg'),
(4, 'Comedor Privado', 'privada','../img/privada1.jpeg'),
(5, 'Terraza VIP', 'terraza','../img/terraza3.jpeg');

INSERT INTO tbl_mesa (id_sala, num_sillas_mesa, estado_mesa) VALUES
(1, 4, 'libre'),
(1, 4, 'libre'),
(1, 6, 'libre'),
(1, 4, 'libre');

INSERT INTO tbl_mesa (id_sala, num_sillas_mesa, estado_mesa) VALUES
(2, 2, 'libre'),
(2, 4, 'libre'),
(2, 6, 'ocupada'),
(2, 4, 'libre');

INSERT INTO tbl_mesa (id_sala, num_sillas_mesa, estado_mesa) VALUES
(3, 2, 'ocupada'),
(3, 4, 'libre'),
(3, 4, 'libre'),
(3, 6, 'ocupada');


