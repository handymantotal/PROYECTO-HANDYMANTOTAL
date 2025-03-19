// Importación de módulos necesarios
import express from "express"; // Express: framework para manejar servidores y rutas HTTP
import fs from "fs"; // Módulo para manejar archivos en el sistema de archivos
import bodyParser from "body-parser"; // Middleware para analizar cuerpos de solicitud en formato JSON

// Inicialización de la aplicación Express
const app = express();
app.use(bodyParser.json()); // Permite que Express interprete JSON en las solicitudes

// Función para leer datos desde un archivo JSON
const readData = () => {
    try {
        const data = fs.readFileSync("./db.json"); // Lee el archivo db.json de forma síncrona
        return JSON.parse(data); // Convierte el contenido JSON en un objeto JavaScript
    } catch (error) {
        console.log(error); // Si hay un error, se muestra en la consola
    }
};
// Función para escribir datos en un archivo JSON
const writeData = (data) => {
    try {
        fs.writeFileSync("./db.json", JSON.stringify(data)); // Convierte el objeto a JSON y lo guarda en el archivo
    } catch (error) {
        console.log(error); // Si hay un error, se muestra en la consola
    }
};
// Ruta de bienvenida - Página principal del API
app.get("/", (req, res) => {
    res.send("Welcome to my first API with Node js!"); // Mensaje de bienvenida en el navegador
});

// Ruta para obtener todos los libros almacenados
app.get("/clientes", (req, res) => {
    const data = readData(); // Lee los datos actuales de la base de datos
    res.json(data.clientes); // Envía la lista de libros en formato JSON
});

// Ruta para obtener un libro específico por su ID
app.get("/clientes/:id", (req, res) => {
    const data = readData(); // Lee los datos actuales
    const id = parseInt(req.params.id); // Convierte el parámetro ID de string a número
    const cliente = data.clientes.find((cliente) => cliente.id === id); // Busca el libro con el ID proporcionado
    res.json(cliente); // Devuelve el libro encontrado en formato JSON
});
// Ruta para agregar un nuevo libro
app.post("/clientes", (req, res) => {
    const data = readData(); // Lee los datos actuales
    const body = req.body; // Extrae la información enviada en la solicitud

    // Crea un nuevo objeto libro con un ID autoincremental
    const newCliente = {
        id: data.clientes.length + 1, // El nuevo ID es el tamaño del array + 1
        ...body, // Usa el operador spread para incluir el contenido del cuerpo de la solicitud
    };
    data.clientes.push(newCliente); // Agrega el nuevo libro al array de libros
    writeData(data); // Guarda los cambios en la base de datos
    res.json(newCliente); // Devuelve el nuevo libro en formato JSON
});
// Ruta para actualizar un libro por su ID
app.put("/clientes/:id", (req, res) => {
    const data = readData(); // Lee los datos actuales
    const body = req.body; // Extrae el contenido del cuerpo de la solicitud
    const id = parseInt(req.params.id); // Convierte el ID a número
    const clienteIndex = data.clientes.findIndex((cliente) => cliente.id === id); // Encuentra el índice del libro

    // Si el índice es válido, actualiza el libro
    if (clienteIndex !== -1) {
        data.clientes[clienteIndex] = {
            ...data.clientes[clienteIndex], // Mantiene los datos originales
            ...body, // Sobrescribe los datos con la nueva información
        };

        writeData(data); // Guarda los cambios en la base de datos
        res.json({ message: "Cliente updated successfully" }); // Mensaje de confirmación
    } else {
        res.status(404).json({ message: "Cliente not found" }); // Retorna un error si el libro no existe
    }
});
// Ruta para eliminar un libro por su ID
app.delete("/clientes/:id", (req, res) => {
    const data = readData(); // Lee los datos actuales
    const id = parseInt(req.params.id); // Convierte el ID a número
    const clienteIndex = data.clientes.findIndex((cliente) => cliente.id === id); // Encuentra el índice del libro

    // Si el índice es válido, elimina el libro
    if (clienteIndex !== -1) {
        data.clientes.splice(clienteIndex, 1); // Elimina un elemento del array en la posición encontrada
        writeData(data); // Guarda los cambios en la base de datos
        res.json({ message: "Cliente deleted successfully" }); // Mensaje de confirmación
    } else {
        res.status(404).json({ message: "Cliente not found" }); // Retorna un error si el libro no existe
    }
});
// Inicia el servidor en el puerto 3000
app.listen(3000, () => {
    console.log("Server listening on port 3000"); // Muestra un mensaje en la consola indicando que el servidor está corriendo
});
