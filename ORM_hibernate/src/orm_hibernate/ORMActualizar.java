package orm_hibernate;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.cfg.Configuration;

public class ORMActualizar {
    
     public static void main(String[] args) {
        // Crear SessionFactory
        try (SessionFactory factory = new Configuration()
                .configure("hibernate.cfg.xml") // Cargar configuración de Hibernate
                .addAnnotatedClass(Cliente.class) // Registrar la entidad
                .buildSessionFactory()) {

            // Iniciar sesión
            try (Session session = factory.openSession()) {
                session.beginTransaction(); // Iniciar transacción

                // 🔹 ID del cliente que queremos actualizar
                int Id_Cliente = 1; // Cambiar según la BD

                // 🔹 Buscar el artista en la base de datos
                Cliente cliente = session.get(Cliente.class, Id_Cliente);

                if (cliente != null) {
                    // 🔹 Actualizar los valores
                    cliente.setNombre("Cristian Torres");
                    cliente.setDireccion("calle4 n 6-6");
                    cliente.setTelefono("45690333");
                    cliente.setCorreo_electronico("cristia@gmail.com");
                     cliente.setContrasena("7777");
                  

                    // 🔹 Guardar cambios
                    session.getTransaction().commit();
                    System.out.println("Cliente actualizado: " + cliente);
                } else {
                    System.out.println("No se encontró el cliente con ID: " + Id_Cliente);
                }

            } catch (Exception e) {
                System.err.println("Error en la actualización: " + e.getMessage());
                e.printStackTrace();
            } finally {
                factory.close(); // Cerrar la fábrica de sesiones
            }

        } catch (Exception e) {
            System.err.println("Error al inicializar Hibernate: " + e.getMessage());
            e.printStackTrace();
        }
    }
}


    

