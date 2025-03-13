package orm_hibernate;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.cfg.Configuration;

public class ORMconsulta {
    
     public static void main(String[] args) {
        // Crear SessionFactory
        try (SessionFactory factory = new Configuration()
                .configure("hibernate.cfg.xml") // Cargar configuración
                .addAnnotatedClass(Cliente.class) // Registrar la entidad
                .buildSessionFactory()) {

            // Iniciar sesión
            try (Session session = factory.openSession()) {
                session.beginTransaction(); // Iniciar transacción

                // 🔹 ID del artista a consultar (DEBE SER UN ENTERO)
                int Id_Cliente = 2; // Cambiar según la BD

                // 🔹 Obtener el artista desde la BD
                Cliente cliente = session.get(Cliente.class, Id_Cliente);

                // 🔹 Mostrar el resultado
                if (cliente != null) {
                    System.out.println("Registro obtenido: " + cliente);
                } else {
                    System.out.println("No se encontró el artista con ID: " + Id_Cliente);
                }

                session.getTransaction().commit(); // Confirmar transacción
                System.out.println("Consulta finalizada correctamente");

            } catch (Exception e) {
                System.err.println("Error en la consulta: " + e.getMessage());
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

    
