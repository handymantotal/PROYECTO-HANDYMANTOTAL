package controlador;  

import java.io.IOException;  
import java.util.List;  
import javax.servlet.RequestDispatcher;  
import javax.servlet.ServletException;  
import javax.servlet.annotation.WebServlet;  
import javax.servlet.http.HttpServlet;  
import javax.servlet.http.HttpServletRequest;  
import javax.servlet.http.HttpServletResponse;  
import modelo.Cliente;  
import modelo.ClienteDAO;  

@WebServlet("/ClienteServlet")  
public class ClienteServlet extends HttpServlet {  
    private static final long serialVersionUID = 1L;  
    private ClienteDAO clienteDAO = new ClienteDAO();  

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {  
        response.setContentType("text/html;charset=UTF-8");  
        try {  
            List<Cliente> lista = clienteDAO.listar();  
            request.setAttribute("listaClientes", lista);  
            RequestDispatcher dispatcher = request.getRequestDispatcher("listar.jsp");  
            dispatcher.forward(request, response);  
        } catch (IOException | ServletException e) {  
            e.printStackTrace();  
            response.sendRedirect("error.jsp"); // Redirigir a una página de error  
        }  
    }  

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {  
        response.setContentType("text/html;charset=UTF-8");
        String nombre = request.getParameter("nombre");
        String direccion = request.getParameter("direccion");  
        String telefono = request.getParameter("telefono");  
        String correo_electronico = request.getParameter("correo_electronico"); 
        String contrasena = request.getParameter("contrasena"); 

      
        if (nombre == null || nombre.trim().isEmpty() || 
            direccion == null || direccion.trim().isEmpty() || 
            telefono == null || telefono.trim().isEmpty() || 
            correo_electronico == null || correo_electronico.trim().isEmpty() || 
            contrasena == null || contrasena.trim().isEmpty() )
             {  
            request.setAttribute("error", "Todos los campos son obligatorios.");  
            RequestDispatcher dispatcher = request.getRequestDispatcher("insertar.jsp");  
            dispatcher.forward(request, response);  
            return;
            
        }  
         Cliente cliente = new Cliente(0, nombre, direccion,telefono,correo_electronico,contrasena);
         clienteDAO.insertar(cliente);
        response.sendRedirect("ClienteServlet");
        // Aquí podrías agregar más validaciones (formato de email, teléfono, etc.)  

    
    }  
}  