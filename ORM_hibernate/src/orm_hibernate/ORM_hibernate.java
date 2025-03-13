package orm_hibernate;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.cfg.Configuration;


public class ORM_hibernate {

    public static void main(String[] args) {
        
        // Crear SessionFactory
        try (SessionFactory factory = new Configuration()
                .configure("hibernate.cfg.xml")
                .addAnnotatedClass(Cliente.class)
                .buildSessionFactory();) {
    
        //iniciar sesion
        
            try(Session session = factory.openSession()) {
                
                session.beginTransaction();//Se da comienzo a la transacción
               
                //crear objeto cliente
                Cliente cliente = new Cliente("Andres Lopez", "cra. 5 numero 32 1", "320987654","lopez5@gmail.com","andres123");
              
                //guardar en la base de datos
                session.save(cliente);
                
                //confirmar transaccion
                session.getTransaction().commit();
                
                System.out.println("El registro fue almacenado correctamente");
            }catch (Exception e){
                System.err.println("Error en la transaccion: " + e.getMessage());
                e.printStackTrace();
            } finally {
                factory.close();
            }
        
        } catch (Exception e){
             System.err.println("Error al inicializar Hibernate :" + e.getMessage());
                        e.printStackTrace();
        }
    
    }
}
  

