import java.util.ArrayList;

import ardoise.*;


public class Chapeaux extends Forme{

	private PointPlan p1;
	private PointPlan p2;
	private PointPlan p3;
	private String type;
	
	public Chapeaux() {}
	
	public Chapeaux(String nom,PointPlan P1,PointPlan P2,PointPlan P3){
		super(nom);
		this.p1=P1;
		this.p2=P2;
		this.p3=P3;
		this.type="C";}
	
	
	public String typeForme() {
		return this.type;
	}
	
	public ArrayList<Segment> dessiner(){
		ArrayList<Segment> rep = new ArrayList<Segment>();
		Segment s1 = new Segment(p1,p2);
		Segment s2 = new Segment(p1,p3);
		rep.add(s1);
		rep.add(s2);
		return rep;
	}
	
	public void deplacer(int deplacementX,int deplacementY) {
		this.p1.deplacer(deplacementX,deplacementY);
		this.p2.deplacer(deplacementX,deplacementY);
		this.p3.deplacer(deplacementX,deplacementY);
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
	
	public String toString() {
		return "Forme " + this.getNomForme() + " de type " + this.type + " formÃ©e des points " + this.p1 + this.p2 + this.p3;
	}
}