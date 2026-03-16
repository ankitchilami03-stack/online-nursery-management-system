import javax.swing.*;
public class JCheckBoxDemo
{
JCheckBoxDemo()
{
JFrame frame=new JFrame("JCheckBox Example");
JCheckBox checkBox1=new JCheckBox("c++");
checkBox1.setBounds(100,100,50,50);
JCheckBox checkBox2=new JCheckBox("java",true);
checkBox2.setBounds(100,150,50,50);
f.add(checkBox1);
f.add(checkBox2);
f.setSize(400,400);
f.setLayout(null);
f.aetVisible(true);
}
public static void main(String[]args)
{
new JCheckBoxDemo();
}
}