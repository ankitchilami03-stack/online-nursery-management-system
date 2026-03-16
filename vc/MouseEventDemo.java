import javax.swing.*;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.awt.event.MouseMotionAdapter;
public class MouseEventDemo
{
public static void main(String[]args)
{
JFrame frame=new JFrame("Mouse Events Demo");
frame.setSize(300,200);
frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
frame.addMouseListener(new MouseAdapter()
{
public void mousePressed(MouseEvent e)
{
System.out.println("Mouse Pressed");
}
public void mouseReleased(MouseEvent e)
{
System.out.println("Mouse Released");
}
});
frame.addMouseMotionListener(new MouseMotionAdapter()
{
public void mouseMoved(MouseEvent e)
{
System.out.println("Mouse Moved");
}});
frame.setVisible(true);
}
}

