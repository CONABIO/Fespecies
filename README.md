<div align="center">

# 🐒 Fichas de Especies (Fespecies)


</div>

---

## 🧠 Descripción

El **Sistema de fichas de especies (Fespecies)** fue desarrollado por [Tu Nombre / Tu Equipo] con el objetivo de... *(agrega aquí una breve descripción extra si lo deseas)*.

---

## 🔥 Empezando

Estas instrucciones te guiarán para obtener una copia del proyecto en funcionamiento en tu máquina local con fines de desarrollo, pruebas y revisión del código. El objetivo es que puedas configurar el entorno, instalar las dependencias y ejecutar la aplicación sin complicaciones.

---

## 🖥️ Prerrequisitos

Asegúrate de tener instalado en tu equipo lo siguiente antes de comenzar:

* **Sistema Operativo:** Windows 10+ / Linux / macOS
* **PHP:** `8.2` o superior
* **Laravel:** `11.x`
* **Base de datos:** MySQL
* **Composer:** `2.8` o superior
* **NPM:** Gestor de paquetes de Node.js
* **Git:** Para clonar y gestionar el repositorio

---


##  🔧 Instalación y levantamiento

```markdown
### 📥 Paso 1: Clonar el proyecto
```
#### Para poder clonar el proyecto se necesita poner el siguiente comando en la consola, se debe acceder a la carpeta donde se quiere clonar el proyecto y ya dentro de la carpeta se abre la terminal y se pone 

```
git clone (URL del repositorio)
```




## Paso 2

#### Ya clonado el proyecto lo que se debe agregar el .env este archivo de texto plano es utilizado para almacenar variables de entorno, como credenciales de bases de datos, claves de API y otras configuraciones sensibles, #### separadas del código fuente, debes generar tu propio archivo .env a partir de este archivo de ejemplo: 

```
cp .env.example .env
```

#### Copiado el archivo puedes empezar a modificar las variables y conexiones a tu base de datos 


## Paso 3

#### Ya agregado el .env lo siguiente es la instalación de dependencias con estos dos comandos

```
composer install
npm install
```


## Paso 4

##### Antes de correr el proyecto es necesario modificar el vite.config.js 

```
server: {
        host: ',  
        port: ,       
        strictPort: true, 
      },
```

#### Se debe modificar el host y poner la ip del equipo donde se va a correr el proyecto y el puerto 

## Paso 5

#### Ya instaladas las dependencias del proyecto y configurado el vite.config.js lo sigueinte es correr el proyecto con los sigueintes comandos

```
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

## Paso 6

##### Como ultimo paso es acceder al navegador en la siguiente URL 

#### http://localhost:8000/
