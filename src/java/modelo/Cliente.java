package modelo;

import java.util.*;
import javax.servlet.*;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;

// Modelo - Cliente.java
public class Cliente 
{
    private int id;
    private String nombre;
    private String direccion;
    private String telefono;
    private String correo_electronico;
    private String contrasena;

    public Cliente() {}
    
    public Cliente(int id, String nombre,String direccion, String telefono, String correo_electronico , String contrasena) 
    {
        this.id = id;
        this.nombre = nombre;
        this.direccion = direccion;
        this.telefono = telefono;
        this.correo_electronico = correo_electronico;
        this.contrasena = contrasena;
    }

    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getNombre() { return nombre; }
    public void setNombre(String nombre) { this.nombre = nombre; }
    public String getDireccion() { return direccion; }
    public void setDireccion(String direccion) { this.direccion = direccion; }
    public String getTelefono() { return telefono; }
    public void setTelefono(String telefono) { this.telefono = telefono; }
    public String getCorreo_electronico() { return correo_electronico; }
    public void setCorreo_electronico(String correo_electronico) { this.correo_electronico = correo_electronico; }
    public String getContrasena() { return contrasena; }
    public void setContrasena(String contrasena) { this.contrasena = contrasena; }
}