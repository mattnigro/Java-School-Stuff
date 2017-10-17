/*
prints out a string of random numbers
 */
package printarray;

import java.security.SecureRandom;


/**
 *
 * @author me
 */
public class PrintArray {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        
        System.out.println("Matthew Nigro - Assignment 6B");
        int randies[] = new int[100];
        for(int i=0;i<=99;i++){
            SecureRandom randy = new SecureRandom();
            int r = randy.nextInt(100);
            randies[i] = r;
        } 
        printArray(randies);
    }
    public static void printArray(int[] arr){
        
        String arrString = "";
        for (int i=0;i<arr.length;i++){
            arrString += arr[i] + " ";
        }
        System.out.print(arrString);
    }
}
