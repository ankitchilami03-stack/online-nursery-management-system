import javax.swing.*;
public class JRadioButton
{
public static void main(String[]args)
{
JFrame frame=new JFrame("JRadio Button Example");
JRadioButton op1=new JRadioButton("MALE");
JRadioButton op2=new JRadioButton("FEMALE");
JRadioButton op3=new JRadioButton("OTHER");
ButtonGroup group=new ButtonGroup();
group.add(op1);
group.add(op2);
group.add(op3);
JPanel panel=new JPanel();
panel.add(op1);
panel.add(op2);
panel.add(op3);
frame.add(panel);
frame.setSize(300,200);
frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
frame.setVisible(true);
}
}