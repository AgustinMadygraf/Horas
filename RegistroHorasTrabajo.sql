CREATE TABLE registro_horas_trabajo (
    id_registro INT AUTO_INCREMENT PRIMARY KEY,
    legajo VARCHAR(4),
    fecha DATE,
    horas_trabajadas DECIMAL(5, 2),
    centro_costo VARCHAR(3),
    sub_centro_costo VARCHAR(3)

);
