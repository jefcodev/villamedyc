// Escribir los script sql que se ingresan a la base de datos

CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(50) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio_c DECIMAL(10, 2) NOT NULL,
  precio_v DECIMAL(10, 2) NOT NULL,
  stock INT DEFAULT 0
);

CREATE TABLE compras (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
);

ALTER TABLE compras
ADD COLUMN proveedor VARCHAR(100);
ALTER TABLE compras
ADD COLUMN factura VARCHAR(100);