// Matthew Nigro - CSIS 220
// Final Part 1 - Student GPA Search with Dialog Box
package finala;

import javax.swing.JOptionPane;

public class FinalA {

    //MAKE MESSAGE CLASS-VISIBLE IN SCOPE
    private static String $message = "Matthew Nigro - Final Project (Part 1)";
    
    public static void main(String[] args) {
        
        // ORDERED ARRAYS
        double[] $_GPA = {4.0, 3.8, 2.7, 3.0, 2.5, 3.3, 1.0, 3.6, 3.5, 2.0};
        int[] $_SID = {1234, 1235, 1236, 1237, 1238, 1239, 1240, 1241, 1242, 1243};
        String[] $_student = {"Matthew", "Mark", "Luke", "John", "Mary", "Peter", "Eve", "Ruth","Teresa", "Ragnar"};
        
        double $GPA = 0.0;
        String $student = "";
        boolean $run = true; // TOKEN TO BEGIN SEARCH OPTION LOOP
        int $SID = 0;
        JOptionPane.showMessageDialog(null, $message);
        
        while($run == true){
          
            $message = "Enter the Student's 4-Digit ID";
            String $StudentID = JOptionPane.showInputDialog($message);

            try{ // CATCH OPTION PANE'S ERROR WHEN USER HITS CANCEL
               
                $SID = Integer.parseInt($StudentID);
            }
            catch (NumberFormatException $err){
                
                break;
            }
            if ($SID >= 1234 && $SID <= 1243){ // ENTERED A VALID STUDENT ID

                int $count = 0;
                for(int $key : $_SID){

                    $count++;
                    if ($SID == $key){ // ITERATE THROUGH ARRAY UNTIL ID IS FOUND

                        break;
                    }
                }
                int $countGPA = 0;
                for (double $xGPA : $_GPA){

                    $countGPA++;
                    if ($countGPA == $count){ // ITERATE SAME NUMBER OF TIMES THROUGH GPA ARRAY

                        $GPA = $xGPA;
                        break;
                    }
                }
                int $countStudent = 0;
                for (String $xstudent : $_student){ // ITERATE SAME NUMBER OF TIMES THROUGH NAME ARRAY

                    $countStudent++;
                    if ($countStudent == $count){

                        $student = $xstudent;
                        break;
                    }
                }
                $message = $student + "'s GPA is " + $GPA; // COMPILE MESSAGE
            }
            else {

                $message = "Cannot find student with ID " + $SID; // COMPILE ERROR MESSAGE
            }
            JOptionPane.showMessageDialog(null, $message); // DISPLAY MESSAGE AND LOOP AGAIN
        }
    }
}
    

