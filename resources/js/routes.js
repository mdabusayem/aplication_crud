
import applicanList from './components/Applicant/List.vue';
import applicantAdd from './components/Applicant/Add.vue';
import applicantEdit from './components/Applicant/Edit.vue';

export const routes = [
  { name: 'applicanList', path: '/', component: applicanList },
  { name: 'applicantAdd', path: '/create', component: applicantAdd },
  { name: 'applicantEdit', path: '/applicant/:id/edit', component: applicantEdit },
  //{ path: '/edit/:id', component: ApplicantEdit, props: true },
];

