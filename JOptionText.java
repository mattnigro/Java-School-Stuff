
package swingbasics;
import javax.swing.JOptionPane;

/**
 *
 * @author Matthew Nigro
 */
public class JOptionText {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
                
        JOptionPane myName = new JOptionPane();
        String name = "";
        while (name.isEmpty()){
           name = (String)JOptionPane.showInputDialog("What's your name?");
        }
        JOptionPane.showMessageDialog(myName,"Cool, " + name + "! Nice to meet you!");
    }
    
}
