<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Clientes</title>
</head>
<body>
    <h2>Lista de Clientes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Telefono</th>
            <th>Correo_electronico</th>
            <th>Contrasena</th>
        </tr>
        <c:forEach var="cliente" items="${listaClientes}">
            <tr>
                <td>${cliente.id}</td>
                <td>${cliente.nombre}</td>
                <td>${cliente.direccion}</td>
                <td>${cliente.telefono}</td>
                <td>${cliente.correo_electronico}</td>
                <td>${cliente.contrasena}</td>
            </tr>
        </c:forEach>
    </table>

    <h3>Agregar Nuevo Cliente</h3>
      
        <form action="ClienteServlet" method="post">  
         Nombre: <input type="text" name="nombre" required>
         Direccion: <input type="text" name="direccion" required>
         Telefono: <input type="text" name="telefono" required>
         Correo_electonico: <input type="text" name="correo_electronico" required>
         Contrasena: <input type="text" name="contrasena" required>
        <input type="submit" value="Agregar Cliente">
    </form>
</body>
</html  