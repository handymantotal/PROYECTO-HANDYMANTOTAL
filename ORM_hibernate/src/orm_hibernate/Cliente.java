package orm_hibernate;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;

@Entity
@Table(name="cliente")

public class Cliente {
    
    @Id
    @GeneratedValue (strategy = GenerationType.IDENTITY)
    @Column(name = "Id_Cliente")
    private int Id_Cliente;
    
    @Column(name = "Nombre")
    private String Nombre;
    
    @Column(name = "Direccion")
    private String Direccion;
    
    @Column(name = "Telefono")
    private String Telefono;
    
    @Column(name = "Correo_electronico")
    private String Correo_electronico;
    
    @Column(name = "Contrasena")
    private String Contrasena;

    public Cliente (){
    }
    
    public Cliente(String Nombre, String Direccion,String Telefono, String Correo_electronico, String Contrasena) {
        this.Nombre = Nombre;
        this.Direccion = Direccion;
        this.Telefono = Telefono;
        this.Correo_electronico = Correo_electronico;
        this.Contrasena = Contrasena;
    }

    public int getId_cliente() {
        return Id_Cliente;
    }

    public void setId_cliente(int Id_Cliente) {
        this.Id_Cliente = Id_Cliente;
    }

    public String getNombre() {
        return Nombre;
    }

    public void setNombre(String Nombre) {
        this.Nombre = Nombre;
    }

    public String getDireccion() {
        return Direccion;
    }

    public void setDireccion(String Direccion) {
        this.Direccion = Direccion;
    }

    public String getTelefono() {
        return Telefono;
    }

    public void setTelefono(String Telefono) {
        this.Telefono = Telefono;
    }

    public String getCorreo_electronico() {
        return Correo_electronico;
    }

    public void setCorreo_electronico(String Correo_electronico) {
        this.Correo_electronico = Correo_electronico;
    }

    public String getContrasena() {
        return Contrasena;
    }

    public void setContrasena(String Contrasena) {
        this.Contrasena = Contrasena;
    }

    @Override
    public String toString() {
        return "Cliente{" + "Id_Cliente=" + Id_Cliente + ", Nombre=" + Nombre + ", Direccion=" + Direccion + ", Telefono=" + Telefono + ", Correo_electronico=" + Correo_electronico + ", Contrasena=" + Contrasena + '}';
    }
}
