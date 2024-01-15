CREATE TABLE centro_costo (
    id_costo INT AUTO_INCREMENT PRIMARY KEY,
    item VARCHAR(10),
    num_descripcion VARCHAR(4),
    descripcion VARCHAR(20)
);

INSERT INTO centro_costo (item, num_descripcion, descripcion) VALUES

('centro', '1', 'Maquina de bolsas'),
('centro', '2', 'Boletas y folleteria'),
('centro', '3', 'logistica'),
('centro', '4', 'Administracion'),
('centro', '5', 'Club'),
('centro', '6', 'Mantenimiento'),
('centro', '7', 'Comedor'),
('centro', '8', 'Guardia'),
('centro', '9', 'Sistemas'),
('centro', '10', 'Enfermeria'),
('sub_centro', '1.a', 'Confección de bolsas de papel'),
('sub_centro', '1.b', 'Impresión  de bolsas de papel'),
('sub_centro', '1.c', 'Confeción y pegado manual de manijas'),
('sub_centro', '1.d', 'Ventas y Marketing de bolsas'),
('sub_centro', '2.a', 'Impresión'),
('sub_centro', '2.b', 'Encuadernación'),
('sub_centro', '2.c', 'Preimpresión'),
('sub_centro', '2.d', 'Despacho');
