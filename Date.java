/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package datetest;

/**
 *
 * @author me
 */
public class Date {
    
    // INSTANCE VARIABLES
    private int $month;
    private int $day;
    private int $year;
    
    //CLASS CONSTRUCTOR
    public Date (int $m, int $d, int $y){
        
        this.$month = $m;
        this.$day = $d;
        this.$year = $y;    
        //System.out.println("1. " + $month);    
        //System.out.println("1. " + $day);    
        //System.out.println("1. " + $year);
    }

    public void $setMonth(int $m){
        
        if ($m > 12){
            $m = 12;
        }
        else if($m < 1){
            $m =1;
        }
        this.$month = $m;    
        //System.out.println("2 M. " + $month);
    }
    public void $setDay(int $d){
                
        if ($d > 31){
            $d = 31;
        }
        else if($d < 1){
            $d =1;
        }
        this.$day = $d;    
        //System.out.println("2 D. " + $day);
    }
    public void $setYear(int $y){
                
        if ($y > 2018){
            $y = 2018;
        }
        else if($y < 1900){
            $y = 1900;
        }
        this.$year = $y;    
        //System.out.println("2 Y. " + $year);
    }
    public int $getMonth(){
            
        //System.out.println("3 M. " + $month);
        return $month;
    }
    public int $getDay(){
            
        //System.out.println("3 D. " + $day);
        return $day;
    }
    public int $getYear(){
            
        //System.out.println("3 Y. " + $year);
        return $year;
    }
    public String $displayDate(){
        
        this.$month = $month;
        //System.out.println("4 M. " + $month);
        $month = $getMonth();        
        this.$day = $day;
        //System.out.println("4 D. " + $day);
        $day = $getDay();        
        this.$year = $year;
        //System.out.println("4 Y. " + $year);
        $year = $getYear();
        return $month + "/" + $day + "/" + $year;
    }
}
