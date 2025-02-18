import { Ref } from 'vue';

interface Image {
  path: string;
}

interface Subcategory {
  name: string;
}

interface Ad {
  id: number;
  title: string;
  price: number;
  images: Image[];
  subcategory?: Subcategory;
}

interface Comment {
  // Définir les propriétés d'un commentaire
}

interface User {
  // Définir les propriétés d'un utilisateur
}

interface AdsData {
sale: any;
  all: Ad[];
}

export function useAds(): {
  showAd: (id: number) => Promise<void>;
  nextPage: Ref<string>;
  statusData: Ref<Ad[]>;
  previousPage: Ref<string>;
  currentPage: Ref<number>;
  totalPages: Ref<number>;
  editData: Ref<Ad>;
  comment: Ref<Comment[]>;
  userData: Ref<User[]>;
  myAdsData: Ref<AdsData>;
  formData: Ref<{ comment: string; ad_id: string; user_id: string | null }>;
  commentData: Ref<Comment[]>;
  addComment: (id: number) => Promise<void>;
  getComment: () => Promise<void>;
  UpdateAd: (id: number) => Promise<void>;
  deleteAd: (id: number) => Promise<void>;
  myAds: () => Promise<void>;
  getUser: () => Promise<void>;
  updateStatus: (id: number) => Promise<void>;
  fetchPageAds: (pageNumber: number) => Promise<void>;
  fetchNextAds: (url: string) => Promise<void>;
  fetchPrevAds: (url: string) => Promise<void>;
  status: () => Promise<number | null>;
};
