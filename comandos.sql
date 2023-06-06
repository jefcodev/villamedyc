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

/*==============================================================*/
/* Table: paquete_cabecera                                      */
/*==============================================================*/
create table paquete_cabecera
(
   cabecera_id          int not null auto_increment,
   usuario_id           int,
   paciente_id          int,
   total                decimal(10,2) not null,
   primary key (cabecera_id),
   FOREIGN KEY (paciente_id) REFERENCES pacientes (id),
   FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
);

/*==============================================================*/
/* Table: paquete_detalle_producto                              */
/*==============================================================*/
create table paquete_detalle_producto
(
   cabecera_id          int,
   producto_id          int,
   costo_producto       decimal(10,2) not null,
   cantidad             int not null,
   total                decimal(10,2) not null,
   FOREIGN KEY (cabecera_id) REFERENCES paquete_cabecera (cabecera_id),
   FOREIGN KEY (producto_id) REFERENCES productos (id)
);

/*==============================================================*/
/* Table: paquete_detalle_servicio                              */
/*==============================================================*/
create table paquete_detalle_servicio
(
   cabecera_id          int,
   servicio_id          int not null,
   numero_sesiones      int not null,
   sesiones_realizadas  int not null,
   total                decimal(10,2) not null,
   FOREIGN KEY (cabecera_id) REFERENCES paquete_cabecera (cabecera_id)
);

/*==============================================================*/
/* Table: historia_clinica_fisio                              */
/*==============================================================*/
create table historia_clinica_fisio
(
   historia_fisio_id          int not null auto_increment,
   paciente_id                int,
   usuario_id                 int,
   numero_historia            varchar(50),
   profesion                  varchar(500),
   tipo_trabajo               varchar(500),
   sedestacion_prolongada     boolean,
   esfuerzo_fisico            int,
   habitos                    varchar(500),
   antecendentes_diagnostico  varchar(500),
   tratamientos_anteriores    varchar(500),
   contracturas               varchar(500),
   irradiacion                boolean,
   hacia_donde                varchar(500),
   intensidad                 varchar(500),
   sensaciones                varchar(500),
   limitacion_movilidad       int,    
   primary key (historia_fisio_id),
   FOREIGN KEY (paciente_id) REFERENCES pacientes (id),
   FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
);


