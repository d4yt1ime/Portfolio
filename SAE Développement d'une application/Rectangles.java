import java.util.ArrayList;

import ardoise.*;

public class Rectangles extends Forme {

	private PointPlan p1;
	private PointPlan p2;
	private PointPlan p3;
	private PointPlan p4;
	private String type;
	
	public Rectangles() {}
	
	public Rectangles(String nom,PointPlan P1,PointPlan P2,PointPlan P3,PointPlan P4){
		super(nom);
		this.p1=P1;
		this.p2=P2;
		this.p3=P3;
		this.p4=P4;
		this.type="R";}
	
	
	public String typeForme() {
		return this.type;
	}
	
	public ArrayList<Segment> dessiner(){
		ArrayList<Segment> rep = new ArrayList<Segment>();
		Segment s1 = new Segment(p1,p2);
		Segment s2 = new Segment(p2,p3);
		Segment s3 = new Segment(p3,p4);
		Segment s4 = new Segment(p4,p1);
		rep.add(s1);
		rep.add(s2);
		rep.add(s3);
		rep.add(s4);
		return rep;
	}
	
	public void deplacer(int deplacementX,int deplacementY) {
		this.p1.deplacer(deplacementX,deplacementY);
		this.p2.deplacer(deplacementX,deplacementY);
		this.p3.deplacer(deplacementX,deplacementY);
		this.p4.deplacer(deplacementX, deplacementY);
		}
	
	
	public PointPlan getPoint1() {
		return this.p1;
	}

	public PointPlan getPoint2() {
		return this.p2;
	}
	
	public PointPlan getPoint3() {
		return this.p3;
	}
	
	public PointPlan getPoint4() {
		return this.p4;
	}
	
	public void setPoint1(PointPlan nouveau) {
		try {
		//Notre point peut etre a l'extremité de l'ardoise mais pas a l'exterieur.
		if (nouveau.getAbscisse()>200 || nouveau.getAbscisse()<0
			|| nouveau.getOrdonnee()>200 || nouveau.getOrdonnee()<0 )
			throw new DepasseCadreException(nouveau.getAbscisse(), nouveau.getOrdonnee());
		this.p1=nouveau;
		}
		catch(DepasseCadreException e){
			e.printStackTrace();
		}
	}
	
	public void setPoint2(PointPlan nouveau) {
		try {
		//Notre point peut etre a l'extremité de l'ardoise mais pas a l'exterieur.
		if (nouveau.getAbscisse()>200 || nouveau.getAbscisse()<0
			|| nouveau.getOrdonnee()>200 || nouveau.getOrdonnee()<0 )
			throw new DepasseCadreException(nouveau.getAbscisse(), nouveau.getOrdonnee());
		this.p2=nouveau;
		}
		catch(DepasseCadreException e){
			e.printStackTrace();
		}
	}
	
	public void setPoint3(PointPlan nouveau) {
		try {
		//Notre point peut etre a l'extremité de l'ardoise mais pas a l'exterieur.
		if (nouveau.getAbscisse()>200 || nouveau.getAbscisse()<0
			|| nouveau.getOrdonnee()>200 || nouveau.getOrdonnee()<0 )
			throw new DepasseCadreException(nouveau.getAbscisse(), nouveau.getOrdonnee());
		this.p3=nouveau;
		}
		catch(DepasseCadreException e){
			e.printStackTrace();
		}
	}
	
	public void setPoint4(PointPlan nouveau) {
		try {
		//Notre point peut etre a l'extremité de l'ardoise mais pas a l'exterieur.
		if (nouveau.getAbscisse()>200 || nouveau.getAbscisse()<0
			|| nouveau.getOrdonnee()>200 || nouveau.getOrdonnee()<0 )
			throw new DepasseCadreException(nouveau.getAbscisse(), nouveau.getOrdonnee());
		this.p4=nouveau;
		}
		catch(DepasseCadreException e){
			e.printStackTrace();
		}
	}
	
	public String toString() {
		return "Forme " + this.getNomForme() + " de type " + this.type + " formÃ©e des points " + this.p1 + this.p2 + this.p3+ this.p4;
	}
}