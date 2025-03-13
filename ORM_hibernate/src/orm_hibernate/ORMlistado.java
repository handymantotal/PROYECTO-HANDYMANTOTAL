package orm_hibernate;

import java.util.List;
import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.cfg.Configuration;


public class ORMlistado {
    
    public static void main(String[] args) {
        // Crear SessionFactory
        try (SessionFactory factory = new Configuration()
                .configure("hibernate.cfg.xml") // Cargar configuración de Hibernate
                .addAnnotatedClass(Cliente.class) // Registrar la entidad
                .buildSessionFactory()) {

            // Iniciar sesión
            try (Session session = factory.openSession()) {
                session.beginTransaction(); // Iniciar transacción

                // 🔹 Obtener la lista completa de artistas
                List<Cliente> clientes = session.createQuery("from Cliente", Cliente.class).getResultList();

                // 🔹 Mostrar los resultados
                if (!clientes.isEmpty()) {
                    System.out.println("Lista de Clientes:");
                    for (Cliente cliente : clientes) {
                        System.out.println(cliente);
                    }
                } else {
                    System.out.println("No hay clientes registrados en la base de datos.");
                }

                session.getTransaction().commit(); // Confirmar transacción
                System.out.println("Listado finalizado correctamente");

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

    
    

