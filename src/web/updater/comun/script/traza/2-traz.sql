--------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de ESTRUCTURA	----
--										----
*/
----------------------------------------
------------------------------------------------------------------------------------------------
/*
--Reglas de confidencialidad																----
--Clasificación: Clasificado.																----
--Forma de distribución: Script SQL.														----
--Este documento contiene información propietaria del CENTRO DE SOLUCIONES DE GESTIÓN 		----
--y es emitido confidencialmente para un propósito específico. 								----
--El que recibe el documento asume la custodia y control, comprometiéndose a no reproducir, ----
--divulgar, difundir o de cualquier manera hacer de conocimiento público su contenido, 		----
--excepto para cumplir el propósito para el cual se ha generado.							----
--Las reglas son aplicables a todo este documento.											----
*/																							
------------------------------------------------------------------------------------------------

--comienza la tranzaccion--
begin;
	-- Propiedades de la BD
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;
	
INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_traza',null,'C','E','I');


	CREATE SCHEMA mod_traza;
------------------------------------------------------------------------------------------------
--Estructura del Esquema Trazas---------------------------------------------------------
------------------------------------------------------------------------------------------------

	
-------------------------------------------------------------------------------
----Creación de secuencias
--------------------------------------------------------------------------------
	SET search_path = mod_traza, pg_catalog;
		
CREATE SEQUENCE his_traza_idtraza_seq
    INCREMENT BY 1
    MAXVALUE 99999999999
    MINVALUE 1
    CACHE 1;
	
	
CREATE SEQUENCE sec_categoriatraza_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 999
    NO MINVALUE
    CACHE 1;
	
	CREATE SEQUENCE sec_tipotraza_seq
    START WITH 1
    INCREMENT BY 1
    MAXVALUE 999
    NO MINVALUE
    CACHE 1;
-------------------------------------------------------------------------------
----Creación de tablas
--------------------------------------------------------------------------------ç
	SET search_path = mod_traza, pg_catalog;
	
	CREATE TABLE his_accion (
    idtraza numeric(19,0) NOT NULL,
    referencia character varying(200),
    controlador character varying(200),
    accion character varying(200),
    inicio numeric(1,0),
    falla numeric(1,0)
);

CREATE TABLE his_dato (
    idtraza numeric(19,0) NOT NULL,
    esquema character varying(200),
    tabla character varying(200),
    accion character varying(200)
);

CREATE TABLE his_excepcion (
    idtraza numeric(19,0) NOT NULL,
    codigo character varying(50),
    tipo character varying(2),
    idioma character varying(10),
    mensaje character varying,
    descripcion text,
    log text
);

CREATE TABLE his_ioc (
    idtraza numeric(19,0) NOT NULL,
    interno character varying(2) NOT NULL,
    origen character varying(255) NOT NULL,
    destino character varying(255) NOT NULL,
    clase character varying(100) NOT NULL,
    metodo character varying(255) NOT NULL,
    accion character varying(255) NOT NULL
);

CREATE TABLE his_iocexception (
    idtraza numeric(19,0),
    mensaje character varying,
    descripcion character varying,
    log character varying,
    origen character varying(100),
    destino character varying(100),
    accion character varying(100),
    controlador character varying(100),
    clase character varying(100),
    metodo character varying(100),
    codigo character varying(20)
);

CREATE TABLE his_rendimiento (
    idtraza numeric(19,0),
    tiempoejecucion numeric(19,10),
    memoria numeric(19,10)
);


CREATE TABLE his_traza (
    idtraza numeric(19,0) DEFAULT nextval('mod_traza.his_traza_idtraza_seq'::regclass) NOT NULL,
    fecha date,
    hora time(6) without time zone,
    idcategoriatraza numeric(19,0) NOT NULL,
    idtipotraza numeric(19,0) NOT NULL,
    usuario character varying(50) NOT NULL,
    idestructuracomun numeric(19,0)
);

CREATE TABLE his_url (
    idtraza numeric(19,0) NOT NULL,
    direccion character varying(200),
    autenticacion character varying(2)
);

CREATE TABLE nom_categoriatraza (
    idcategoriatraza numeric(19,0) DEFAULT nextval('mod_traza.sec_categoriatraza_seq'::regclass) NOT NULL,
    denominacion character varying(100)
);

CREATE TABLE nom_tipotraza (
    idtipotraza numeric(19,0) DEFAULT nextval('mod_traza.sec_tipotraza_seq'::regclass) NOT NULL,
    tipotraza character varying(100)
);

CREATE TABLE "mod_traza"."his_autentication" (
  "idtraza" NUMERIC(19,0) NOT NULL, 
  "ip_pc" VARCHAR(40) NOT NULL, 
  CONSTRAINT "his_autentication_pkey" PRIMARY KEY("idtraza")
 
) WITHOUT OIDS;

CREATE TABLE "mod_traza"."his_cierresesion" (
  "idtraza" NUMERIC(19,0) NOT NULL, 
  "ip_pc" VARCHAR(40) NOT NULL, 
  CONSTRAINT "id_cierre_seccion" PRIMARY KEY("idtraza")
) WITHOUT OIDS;
-------------------------------------------------------------------------------
----Creación de llaves primarias
--------------------------------------------------------------------------------
	SET search_path = mod_traza, pg_catalog;
	
ALTER TABLE ONLY his_accion
    ADD CONSTRAINT his_accion_pkey PRIMARY KEY (idtraza);

ALTER TABLE ONLY his_excepcion
    ADD CONSTRAINT his_excepcion_pkey PRIMARY KEY (idtraza);

ALTER TABLE ONLY his_ioc
    ADD CONSTRAINT his_ioc_pkey PRIMARY KEY (idtraza);

ALTER TABLE ONLY his_traza
    ADD CONSTRAINT his_traza_pkey PRIMARY KEY (idtraza);

ALTER TABLE ONLY nom_categoriatraza
    ADD CONSTRAINT nom_categoriatraza_pkey PRIMARY KEY (idcategoriatraza);

ALTER TABLE ONLY nom_tipotraza
    ADD CONSTRAINT nom_tipotraza_pkey PRIMARY KEY (idtipotraza);
-------------------------------------------------------------------------------
----Creación de otras restricciones de datos
--------------------------------------------------------------------------------
	SET search_path = mod_traza, pg_catalog;
	
-------------------------------------------------------------------------------
----Creación de indices
--------------------------------------------------------------------------------
	SET search_path = mod_traza, pg_catalog;
	
	CREATE INDEX his_traza_idx ON his_traza USING btree (fecha);
-------------------------------------------------------------------------------
----Creación de tipos de datos
--------------------------------------------------------------------------------
	SET search_path = mod_traza, pg_catalog;
	
-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
	SET search_path = mod_traza, pg_catalog;
	
	
ALTER TABLE ONLY his_accion
    ADD CONSTRAINT fkhis_accion219887 FOREIGN KEY (idtraza) REFERENCES his_traza(idtraza);

ALTER TABLE ONLY his_dato
    ADD CONSTRAINT fkhis_dato345299 FOREIGN KEY (idtraza) REFERENCES his_traza(idtraza);

ALTER TABLE ONLY his_excepcion
    ADD CONSTRAINT fkhis_excepc922902 FOREIGN KEY (idtraza) REFERENCES his_traza(idtraza);

ALTER TABLE ONLY his_ioc
    ADD CONSTRAINT fkhis_ioc509659 FOREIGN KEY (idtraza) REFERENCES his_traza(idtraza);

ALTER TABLE ONLY his_iocexception
    ADD CONSTRAINT fkhis_iocexc24656 FOREIGN KEY (idtraza) REFERENCES his_traza(idtraza);

ALTER TABLE ONLY his_traza
    ADD CONSTRAINT fkhis_traza278698 FOREIGN KEY (idtipotraza) REFERENCES nom_tipotraza(idtipotraza);

ALTER TABLE ONLY his_traza
    ADD CONSTRAINT fk_hist_traza_categoriatraza FOREIGN KEY (idcategoriatraza) REFERENCES mod_traza.nom_categoriatraza (idcategoriatraza) MATCH SIMPLE ON UPDATE CASCADE ON DELETE NO ACTION;

ALTER TABLE ONLY his_url
    ADD CONSTRAINT fkhis_url521293 FOREIGN KEY (idtraza) REFERENCES his_traza(idtraza);

ALTER TABLE ONLY his_autentication
    ADD CONSTRAINT "fkhis_autentication" FOREIGN KEY ("idtraza")
    REFERENCES "mod_traza"."his_traza"("idtraza")
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;
	
ALTER TABLE ONLY his_cierresesion
    ADD CONSTRAINT "fkhis_cierre_seccion" FOREIGN KEY ("idtraza")
    REFERENCES "mod_traza"."his_traza"("idtraza")
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE;





--fin de la tranzaccion--
COMMIT;