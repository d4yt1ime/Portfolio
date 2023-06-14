import java.util.ArrayList;
import ardoise.*;

public class Test {
	
	public static void main(String args[]) throws InterruptedException {
		
		PointPlan p1= new PointPlan(118,13);
		PointPlan p2= new PointPlan(123,20);
		PointPlan p3= new PointPlan(128,13);
		PointPlan p4= new PointPlan(133,30);
		PointPlan p5= new PointPlan(136,32);
		PointPlan p6= new PointPlan(138,30);
		PointPlan p7= new PointPlan(142,14);
		PointPlan p8= new PointPlan(144,17);
		PointPlan p9= new PointPlan(146,14);
		PointPlan p10= new PointPlan(9,100);
		PointPlan p11= new PointPlan(20,100);
		PointPlan p12= new PointPlan(20,198);
		PointPlan p13= new PointPlan(9,198);
		PointPlan p15= new PointPlan(80,140);
		PointPlan p16= new PointPlan(180,140);
		PointPlan p17= new PointPlan(180,198);
		PointPlan p18= new PointPlan(80,198);
		PointPlan p20= new PointPlan(130,100);
		PointPlan p21= new PointPlan(120,170);
		PointPlan p22= new PointPlan(140,170);
		PointPlan p23= new PointPlan(140,198);
		PointPlan p24= new PointPlan(120,198);
		PointPlan p26= new PointPlan(170,52);
		PointPlan p27= new PointPlan(173,45);
		PointPlan p28= new PointPlan(177,52);
		PointPlan p29= new PointPlan(184,57);
		PointPlan p30= new PointPlan(177,60);
		PointPlan p31= new PointPlan(174,66);
		PointPlan p32= new PointPlan(170,60);
		PointPlan p33= new PointPlan(164,57);
		PointPlan p34= new PointPlan(3,14);
		PointPlan p35= new PointPlan(43,3);
		PointPlan p36= new PointPlan(112,14);
		PointPlan p37= new PointPlan(152,7);
		PointPlan p38= new PointPlan(166,3);
		PointPlan p39= new PointPlan(172,7);
		//Test d'exception
		//PointPlan pTest= new PointPlan(500,7);
		
		Chapeaux c1 = new Chapeaux("Oiseau1",p2,p1,p3);
		Chapeaux c2 = new Chapeaux("Oiseau2",p5,p4,p6);
		Chapeaux c3 = new Chapeaux("Oiseau3",p8,p7,p9);
		Chapeaux c4 = new Chapeaux("Toit maison",p20,p15,p16);
		Chapeaux c5 = new Chapeaux("Branche1",p27,p26,p28);
		Chapeaux c6 = new Chapeaux("Branche2",p29,p28,p30);
		Chapeaux c7 = new Chapeaux("Branche3",p31,p30,p32);
		Chapeaux c8 = new Chapeaux("Branche4",p33,p32,p26);
		
		Rectangles r1=new Rectangles("Tour",p10,p11,p12,p13);
		Rectangles r2=new Rectangles("Corps maison",p15,p16,p17,p18);
		Rectangles r3=new Rectangles("Porte maison",p21,p22,p23,p24);
		
		Triangles t1=new Triangles("Montagne1",p34,p35,p36);
		Triangles t2=new Triangles("Montagne2",p37,p38,p39);
		//Test d'exception
		//t1.setPoint1(pTest);
		
		ArrayList<Forme> struct_maison= new ArrayList<Forme>();
		struct_maison.add(c4);
		struct_maison.add(r2);
		struct_maison.add(r3);
		GrandeForme GF1=new GrandeForme("Maison",struct_maison);
		
		ArrayList<Forme> struct_etoile= new ArrayList<Forme>();
		struct_etoile.add(c5);
		struct_etoile.add(c6);
		struct_etoile.add(c7);
		struct_etoile.add(c8);
		GrandeForme GF2=new GrandeForme("Ã©toile",struct_etoile);

		Ardoise vierge= new Ardoise();
		vierge.ajouterForme(c1);
		vierge.ajouterForme(c2);
		vierge.ajouterForme(c3);
		vierge.ajouterForme(r1);
		vierge.ajouterForme(t1);
		vierge.ajouterForme(t2);
		vierge.ajouterForme(GF1);
		vierge.ajouterForme(GF2);
		
		vierge.dessinerGraphique();
		
		try {
		Thread.sleep(1000);
		}
		finally{
		vierge.deplacer("C", 10, 20);
		
		}

			}
	}