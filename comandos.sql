/* Escribir los script sql que se ingresan a la base de datos*/

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
   paquete_id           int not null auto_increment,
   titulo_paquete       varchar(500) not null,
   tipo_paquete         int not null,
   numero_sesiones      int not null,
   total                decimal(10,2) not null,
   primary key (paquete_id)
);

/*==============================================================*/
/* Table: paquete_detalle                                       */
/*==============================================================*/
create table paquete_detalle
(
   paquete_id           int,
   pro_ser_id           int,
   nombre               varchar(1240) not null,
   tipo                 varchar(100) not null,
   costo                decimal(10,2) not null,
   cantidad             int not null,
   total                decimal(10,2) not null,
   FOREIGN KEY (paquete_id) REFERENCES paquete_cabecera (paquete_id)
);

/*==============================================================*/
/* Table: consultas_fisioterapeuta                              */
/*==============================================================*/
create table consultas_fisioterapeuta
(
   consulta_fisio_id          int not null auto_increment,
   paciente_id                int,
   usuario_id                 int,
   paquete_id                 int,
   numero_historia            varchar(50),
   fecha                      datetime,
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
   estado_atencion            int,
   primary key (consulta_fisio_id),
   FOREIGN KEY (paciente_id) REFERENCES pacientes (id),
   FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
   FOREIGN KEY (paquete_id) REFERENCES paquete_cabecera (paquete_id)
);


