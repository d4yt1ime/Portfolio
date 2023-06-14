import java.util.ArrayList;

import ardoise.*;

public class GrandeForme extends Forme {

	private ArrayList<Forme> formes;
	private String type;
	
	public GrandeForme() {}
	
	public GrandeForme(String nom,ArrayList<Forme> liste){
		super(nom);
		this.formes=liste;
		this.type="GF";}
	
	
	public String typeForme() {
		return this.type;
	}
	
	public ArrayList<Segment> dessiner(){
		ArrayList<Segment> rep= new ArrayList<Segment>();
		for(int i=0;i<this.formes.size();i++) {
			ArrayList<Segment> temp=this.formes.get(i).dessiner();
			for(int x=0;x<temp.size();x++) {
				rep.add(temp.get(x));
			}		
		}	
		return rep;
	}
	
	public void deplacer(int deplacementX,int deplacementY){
		ArrayList<PointPlan> temp1 = new ArrayList<PointPlan>();
		for(int i=0;i<this.formes.size();i++) {
			ArrayList<Segment> temp2=this.formes.get(i).dessiner();
			for(int x=0;x<temp2.size();x++) {
				PointPlan p1=temp2.get(x).getPointDepart();
				PointPlan p2=temp2.get(x).getPointArrivee();
				if(temp1.contains(p1)==false) {
					temp1.add(p1);
				}
				if(temp1.contains(p2)==false) {
					temp1.add(p2);
					}}}
		System.out.println(temp1);
		for(int y=0;y<temp1.size();y++) {
			temp1.get(y).deplacer(deplacementX,deplacementY);
		}}
	
	public ArrayList<Forme> getFormes(){
		return this.formes;
	}
	
	public String toString() {
		String rep = "";
		rep = rep+ "Grande Formes " + this.getNomForme() + " de type " + this.type + " formÃ©e des formes:" + "\n";
		for(int i=0;i<this.formes.size();i++) {
			rep = rep + this.formes.get(i)+ "\n";
		}
		return rep;
		}
	}