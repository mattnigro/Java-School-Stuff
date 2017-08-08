// Matthew Nigro - CSIS 220
// Assignment 6 - CREATE AND MODIFY DATE OBJECTS
package datetest;

import java.util.Scanner;

public class DateTest {

    public static void main(String[] args) {
                              
        System.out.println("Matthew Nigro - Assignment 6\n");
        Scanner $input = new Scanner(System.in);
        System.out.print("Enter the current Month (01-12): ");
        int $month = $input.nextInt();
        System.out.print("Enter the current Day (01-31): ");
        int $day = $input.nextInt();
        System.out.print("Enter the current Year (1900-2018): ");
        int $year = $input.nextInt();
        Date $Date1 = new Date($month,$day,$year);
        System.out.println("Thank you!");
        
        System.out.print("Enter your birth Month (01-12): ");
        $month = $input.nextInt();
        System.out.print("Enter your birth Day (01-31): ");
        $day = $input.nextInt();
        System.out.print("Enter your birth Year (1900-2018): ");
        $year = $input.nextInt();
        Date $Date2 = new Date($month,$day,$year);

        System.out.println("\nObject Date1 is set to: " + $Date1.$displayDate());
        System.out.println("\nObject Date2 is set to: " + $Date2.$displayDate());
        
        System.out.print("\nThank you. What year was 10 years ago: ");
        $year = $input.nextInt();
        $Date1.$setYear($year);
        System.out.println("\nObject Date1 is changed to: " + $Date1.$displayDate());
        
    }
    
}
