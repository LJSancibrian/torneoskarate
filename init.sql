SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS playoff;

-- Crear el usuario con la contraseña especificada
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'playoffps';

-- Otorgar todos los privilegios al usuario sobre la base de datos
GRANT ALL PRIVILEGES ON playoff.* TO 'root'@'%';


-- Aplicar los cambios
FLUSH PRIVILEGES;