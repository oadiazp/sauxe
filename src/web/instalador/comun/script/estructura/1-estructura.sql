--------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de ROLES    	----
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
	
	
CREATE ROLE estructurasigis NOSUPERUSER NOINHERIT NOCREATEDB NOCREATEROLE LOGIN; 
ALTER role estructurasigis ENCRYPTED PASSWORD 'md570f1a4d9fa74d8dd45f9ec5436e97822';

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_estructuracomp',null,'C','R','I');
	
--termina la tranzaccion--
commit; 