package modelo;  

import java.sql.*;  
import java.util.*;  

public class ClienteDAO {  
    private Connection con;  
    private PreparedStatement ps;  
    private ResultSet rs;  
    private String url = "jdbc:mysql://localhost:3306/handymantotal";  
    private String user = "root";  
    private String password = "Hecaro.0920";  

    public ClienteDAO() {  
        try {  
            Class.forName("com.mysql.cj.jdbc.Driver");  
            con = DriverManager.getConnection(url, user, password);  
        } catch (Exception e) {  
            e.printStackTrace();  
        }  
    }  

    public List<Cliente> listar() {  
        List<Cliente> lista = new ArrayList<>();  
        PreparedStatement ps = null;  
        ResultSet rs = null;  
        try {  
            if (con == null) { // Verifica si la conexión es nula  
                System.err.println("La conexión es nula. No se puede ejecutar la consulta.");   
                return lista; // Retorna la lista vacía si no hay conexión  
            }  
            ps = con.prepareStatement("SELECT * FROM cliente");  
            rs = ps.executeQuery();  
            while (rs.next()) {  
                lista.add(new Cliente(  
                    rs.getInt("ID_cliente"),  
                    rs.getString("Nombre"),  
                    rs.getString("Direccion"),  
                    rs.getString("Telefono"),  
                    rs.getString("Correo_electronico"),  
                    rs.getString("Contrasena")  
                ));  
            }  
        } catch (SQLException e) {  
            System.err.println("Error al ejecutar la consulta: " + e.getMessage());  
            e.printStackTrace(); // Considera usar logging  
        } finally {  
            cerrarRecursos(rs, ps);  
        }  
        return lista;  
    }  

    private void cerrarRecursos(ResultSet rs, PreparedStatement ps) {  
        try {  
            if (rs != null) rs.close();  
            if (ps != null) ps.close();  
        } catch (SQLException e) {  
            System.err.println("Error al cerrar recursos: " + e.getMessage());  
            e.printStackTrace(); // Considera usar logging  
        }  
    }  

    public void insertar(Cliente cliente) {  
        PreparedStatement ps = null;  
        try {  
            // SQL para insertar un nuevo cliente en la tabla  
            String sql = "INSERT INTO cliente (Nombre, Direccion, Telefono, Correo_electronico, Contrasena) VALUES (?, ?, ?, ?, ?)";  
            ps = con.prepareStatement(sql);  
            
            // Establecer los parámetros del PreparedStatement  
            ps.setString(1, cliente.getNombre());  
            ps.setString(2, cliente.getDireccion());  
            ps.setString(3, cliente.getTelefono());  
            ps.setString(4, cliente.getCorreo_electronico());  
            ps.setString(5, cliente.getContrasena());  
            
            // Ejecutar la inserción  
            ps.executeUpdate();  
        } catch (SQLException e) {  
            System.err.println("Error al insertar el cliente: " + e.getMessage());  
            e.printStackTrace(); // Considera usar logging  
        } finally {  
            // Cerrar recursos  
            if (ps != null) {  
                try {  
                    ps.close();  
                } catch (SQLException e) {  
                    System.err.println("Error al cerrar el PreparedStatement: " + e.getMessage());  
                }  
            }  
        }  
    }  
}  