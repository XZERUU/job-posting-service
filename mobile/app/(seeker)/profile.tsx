// ============================================================
// Profile Management
// ============================================================
import React, { useEffect, useState } from 'react';
import {
  View, Text, StyleSheet, ScrollView, Alert, TouchableOpacity, KeyboardAvoidingView, Platform,
} from 'react-native';
import { useRouter } from 'expo-router';
import * as DocumentPicker from 'expo-document-picker';
import { Button, Input, Card } from '../../src/components/ui';
import { api, getApiError } from '../../src/api/client';
import { Colors, Spacing, FontSize, Radius } from '../../src/constants/theme';
import { confirmAction } from '../../src/utils/confirm';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function ProfileScreen() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [passwordSaving, setPasswordSaving] = useState(false);
  const [deleting, setDeleting] = useState(false);
  
  // Basic profile
  const [form, setForm] = useState<any>({
    name: '', email: '', phone: '', location: '', headline: '', about: '',
    linkedin_url: '', portfolio_url: '', github_url: ''
  });
  
  const [skillsStr, setSkillsStr] = useState('');
  
  // Complex items
  const [education, setEducation] = useState<any>({
    degree: '', school: '', year_from: '', year_to: ''
  });
  
  const [experience, setExperience] = useState<any>({
    position: '', company: '', year_from: '', year_to: ''
  });

  // Password
  const [passwordForm, setPasswordForm] = useState({
    current_password: '', password: '', password_confirmation: ''
  });

  // Resume
  const [resumeFile, setResumeFile] = useState<any>(null);
  const [existingResumeUrl, setExistingResumeUrl] = useState<string | null>(null);

  const goToDashboard = () => {
    router.replace('/(seeker)/dashboard');
  };

  useEffect(() => {
    (async () => {
      try {
        const p = await api.get('/job-seeker/profile');
        const prof = p.data.profile;
        const skillsArr = p.data.skills || [];
        
        setForm({
          name: prof.name || '',
          email: prof.email || '',
          phone: prof.phone || '',
          location: prof.location || '',
          headline: prof.headline || '',
          about: prof.about || '',
          linkedin_url: prof.linkedin_url || '',
          portfolio_url: prof.portfolio_url || '',
          github_url: prof.github_url || ''
        });
        
        setExistingResumeUrl(prof.resume_path);
        setSkillsStr(skillsArr.join(', '));
        
        if (prof.education && prof.education.length > 0) {
          setEducation(prof.education[0]);
        }
        
        if (prof.experiences && prof.experiences.length > 0) {
          setExperience(prof.experiences[0]);
        }
      } catch (err) {
        Alert.alert('Error', getApiError(err));
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const setField = (k: string, v: any) => setForm((f: any) => ({ ...f, [k]: v }));
  const setEdu = (k: string, v: any) => setEducation((f: any) => ({ ...f, [k]: v }));
  const setExp = (k: string, v: any) => setExperience((f: any) => ({ ...f, [k]: v }));
  const setPwd = (k: string, v: any) => setPasswordForm((f: any) => ({ ...f, [k]: v }));

  const pickResume = async () => {
    try {
      const result = await DocumentPicker.getDocumentAsync({
        type: 'application/pdf',
        copyToCacheDirectory: true,
      });
      if (result.canceled === false && result.assets && result.assets.length > 0) {
        setResumeFile(result.assets[0]);
      }
    } catch (err) {
      console.warn(err);
    }
  };

  const handleSaveProfile = async () => {
    if (!form.name) {
      Alert.alert('Required', 'Name is required.');
      return;
    }
    setSaving(true);
    try {
      const skillsArr = skillsStr.split(',').map(s => s.trim()).filter(Boolean);
      
      const formData = new FormData();
      formData.append('name', form.name);
      formData.append('phone', form.phone);
      formData.append('location', form.location);
      formData.append('headline', form.headline);
      formData.append('about', form.about);
      formData.append('linkedin_url', form.linkedin_url);
      formData.append('portfolio_url', form.portfolio_url);
      formData.append('github_url', form.github_url);
      
      formData.append('skills', JSON.stringify(skillsArr));
      formData.append('education', JSON.stringify(education.degree ? [education] : []));
      formData.append('experiences', JSON.stringify(experience.position ? [experience] : []));

      if (resumeFile) {
        formData.append('resume', {
          uri: resumeFile.uri,
          name: resumeFile.name,
          type: resumeFile.mimeType || 'application/pdf',
        } as any);
      }
      
      await api.post('/job-seeker/profile', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      
      Alert.alert('Saved', 'Profile updated successfully.');
    } catch (err) {
      Alert.alert('Error', getApiError(err));
    } finally {
      setSaving(false);
    }
  };

  const handleSavePassword = async () => {
    if (!passwordForm.current_password || !passwordForm.password || !passwordForm.password_confirmation) {
      Alert.alert('Error', 'Please fill all password fields');
      return;
    }
    setPasswordSaving(true);
    try {
      await api.post('/job-seeker/password', passwordForm);
      Alert.alert('Success', 'Password updated successfully');
      setPasswordForm({ current_password: '', password: '', password_confirmation: '' });
    } catch (err) {
      Alert.alert('Error', getApiError(err));
    } finally {
      setPasswordSaving(false);
    }
  };

  const handleDeleteAccount = () => {
    confirmAction(
      'Delete Account',
      'Once your account is deleted, all of its resources and data will be permanently deleted.',
      async () => {
        setDeleting(true);
        try {
          await api.delete('/job-seeker/account');
          await AsyncStorage.removeItem('peso_token');
          await AsyncStorage.removeItem('peso_user');
          router.replace('/(public)/welcome' as any);
        } catch (err) {
          Alert.alert('Error', getApiError(err));
        } finally {
          setDeleting(false);
        }
      },
      'DELETE ACCOUNT',
      true
    );
  };

  if (loading) {
    return <View style={styles.center}><Text style={styles.loadingText}>Loading...</Text></View>;
  }

  return (
    <KeyboardAvoidingView style={{ flex: 1 }} behavior={Platform.OS === 'ios' ? 'padding' : 'height'}>
      <ScrollView style={styles.container} contentContainerStyle={styles.content} keyboardShouldPersistTaps="handled">
        <View style={styles.header}>
          <TouchableOpacity testID="profile-back" onPress={goToDashboard} activeOpacity={0.75} style={styles.backButton}>
            <Text style={styles.backText}>{'< Back'}</Text>
          </TouchableOpacity>
          <Text style={styles.kicker}>MY PROFILE</Text>
          <Text style={styles.headerTitle}>Account Settings</Text>
          <Text style={styles.headerSub}>Manage your account information, profile details, and resume.</Text>
        </View>

        <View style={styles.body}>
          {/* Account information */}
          <ProfileSection title="Account information">
            <Text style={styles.sectionSub}>Update your account's profile information and email address.</Text>
            <View style={{ height: Spacing.md }} />
            <Input label="Name" value={form.name} onChangeText={(v) => setField('name', v)} autoCapitalize="words" />
            <Input label="Email" value={form.email} editable={false} onChangeText={() => {}} />
            {/* Note: Web requires Save button for this section, but we'll combine saving in the main Job Seeker profile save for mobile simplicity, or we can just have one Save Profile button for all profile text fields */}
          </ProfileSection>

          {/* Professional links */}
          <ProfileSection title="Professional links">
            <Input label="LinkedIn URL" value={form.linkedin_url} onChangeText={(v) => setField('linkedin_url', v)} placeholder="https://linkedin.com/in/yourname" keyboardType="url" autoCapitalize="none" />
            <Input label="Portfolio website" value={form.portfolio_url} onChangeText={(v) => setField('portfolio_url', v)} placeholder="https://yourportfolio.com" keyboardType="url" autoCapitalize="none" />
            <Input label="GitHub" value={form.github_url} onChangeText={(v) => setField('github_url', v)} placeholder="https://github.com/yourusername" keyboardType="url" autoCapitalize="none" />
          </ProfileSection>

          {/* Job seeker profile */}
          <ProfileSection title="Job seeker profile">
            <View style={styles.twoColumn}>
              <View style={{ flex: 1 }}>
                <Input label="Phone Number" value={form.phone} onChangeText={(v) => setField('phone', v)} keyboardType="phone-pad" />
              </View>
              <View style={{ width: Spacing.sm }} />
              <View style={{ flex: 1 }}>
                <Input label="Location" value={form.location} onChangeText={(v) => setField('location', v)} placeholder="City, Province" autoCapitalize="words" />
              </View>
            </View>
            
            <Input label="Professional Headline" value={form.headline} onChangeText={(v) => setField('headline', v)} placeholder="e.g. Aspiring Web Developer" autoCapitalize="words" />
            <Input label="About" value={form.about} onChangeText={(v) => setField('about', v)} multiline numberOfLines={3} placeholder="Write a short summary for employers." />
            
            <Input label="Skills" value={skillsStr} onChangeText={setSkillsStr} placeholder="Separate skills with commas." />
            <Text style={styles.helpText}>Separate skills with commas.</Text>

            <View style={{ marginTop: Spacing.sm, marginBottom: Spacing.md }}>
              <Text style={styles.label}>Resume (PDF)</Text>
              <View style={styles.filePickerRow}>
                <TouchableOpacity style={styles.fileButton} onPress={pickResume}>
                  <Text style={styles.fileButtonText}>Choose File</Text>
                </TouchableOpacity>
                <Text style={styles.fileText} numberOfLines={1}>
                  {resumeFile ? resumeFile.name : (existingResumeUrl ? 'Resume uploaded' : 'No file chosen')}
                </Text>
              </View>
            </View>

            <Text style={styles.subHeading}>Education</Text>
            <View style={styles.twoColumn}>
              <View style={{ flex: 1 }}><Input label="Degree or course" value={education.degree} onChangeText={(v) => setEdu('degree', v)} /></View>
              <View style={{ width: Spacing.sm }} />
              <View style={{ flex: 1 }}><Input label="School" value={education.school} onChangeText={(v) => setEdu('school', v)} /></View>
            </View>
            <View style={styles.twoColumn}>
              <View style={{ flex: 1 }}><Input label="Start year" value={education.year_from} onChangeText={(v) => setEdu('year_from', v)} keyboardType="numeric" /></View>
              <View style={{ width: Spacing.sm }} />
              <View style={{ flex: 1 }}><Input label="End year" value={education.year_to} onChangeText={(v) => setEdu('year_to', v)} keyboardType="numeric" /></View>
            </View>

            <Text style={styles.subHeading}>Work experience</Text>
            <View style={styles.twoColumn}>
              <View style={{ flex: 1 }}><Input label="Position" value={experience.position} onChangeText={(v) => setExp('position', v)} /></View>
              <View style={{ width: Spacing.sm }} />
              <View style={{ flex: 1 }}><Input label="Company" value={experience.company} onChangeText={(v) => setExp('company', v)} /></View>
            </View>
            <View style={styles.twoColumn}>
              <View style={{ flex: 1 }}><Input label="Start year" value={experience.year_from} onChangeText={(v) => setExp('year_from', v)} keyboardType="numeric" /></View>
              <View style={{ width: Spacing.sm }} />
              <View style={{ flex: 1 }}><Input label="End year or Present" value={experience.year_to} onChangeText={(v) => setExp('year_to', v)} /></View>
            </View>

            <View style={{ marginTop: Spacing.md }}>
              <Button title="Save Profile Details" onPress={handleSaveProfile} loading={saving} />
            </View>
          </ProfileSection>

          {/* Change password */}
          <ProfileSection title="Change password">
            <Text style={styles.sectionSub}>Update Password</Text>
            <Text style={[styles.sectionSub, { color: Colors.gray }]}>Ensure your account is using a long, random password to stay secure.</Text>
            <View style={{ height: Spacing.md }} />
            
            <Input label="Current Password" value={passwordForm.current_password} onChangeText={(v) => setPwd('current_password', v)} secureTextEntry />
            <Input label="New Password" value={passwordForm.password} onChangeText={(v) => setPwd('password', v)} secureTextEntry />
            <Input label="Confirm Password" value={passwordForm.password_confirmation} onChangeText={(v) => setPwd('password_confirmation', v)} secureTextEntry />
            
            <View style={{ alignItems: 'flex-start', marginTop: Spacing.sm }}>
              <Button title="SAVE" onPress={handleSavePassword} loading={passwordSaving} variant="secondary" />
            </View>
          </ProfileSection>

          {/* Danger zone */}
          <ProfileSection title="Danger zone" customStyle={{ borderColor: Colors.error, borderWidth: 1, backgroundColor: '#FEF2F2' }}>
            <Text style={[styles.sectionSub, { color: Colors.gray, marginBottom: Spacing.sm }]}>Deleting your account is permanent and cannot be undone.</Text>
            <Text style={styles.dangerHeading}>Delete Account</Text>
            <Text style={[styles.sectionSub, { color: Colors.gray }]}>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</Text>
            
            <View style={{ alignItems: 'flex-start', marginTop: Spacing.lg }}>
              <Button title="DELETE ACCOUNT" onPress={handleDeleteAccount} loading={deleting} variant="danger" />
            </View>
          </ProfileSection>

          <View style={{ height: Spacing.xl }} />
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

function ProfileSection({ title, children, customStyle }: { title: string; children: React.ReactNode; customStyle?: any }) {
  return (
    <Card style={StyleSheet.flatten([styles.sectionCard, customStyle]) as any}>
      <Text style={styles.formTitle}>{title}</Text>
      <View style={styles.divider} />
      <View style={{ paddingTop: Spacing.sm }}>
        {children}
      </View>
    </Card>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.lightBg },
  content: { paddingBottom: Spacing.xl },
  center: { flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: Colors.lightBg },
  loadingText: { color: Colors.textDark, fontSize: FontSize.md },
  header: {
    backgroundColor: Colors.primaryDark,
    paddingHorizontal: Spacing.lg,
    paddingTop: Spacing.lg,
    paddingBottom: Spacing.lg,
  },
  backButton: { alignSelf: 'flex-start', minHeight: 44, justifyContent: 'center', marginBottom: Spacing.sm },
  backText: { color: Colors.white, fontSize: FontSize.sm, fontWeight: '900' },
  kicker: { color: Colors.cardHighlight, fontSize: FontSize.xs, fontWeight: '900' },
  headerTitle: { color: Colors.white, fontSize: FontSize.xl, fontWeight: '900', marginTop: 4 },
  headerSub: { color: Colors.cardHighlight, fontSize: FontSize.sm, lineHeight: 20, marginTop: 8 },
  body: { padding: Spacing.md },
  sectionCard: { marginBottom: Spacing.lg, borderRadius: Radius.lg },
  formTitle: { fontSize: FontSize.lg, fontWeight: '900', color: Colors.textDark, marginBottom: Spacing.sm },
  divider: { height: 1, backgroundColor: Colors.border, marginBottom: Spacing.sm },
  sectionSub: { fontSize: FontSize.sm, color: Colors.textDark, lineHeight: 20 },
  subHeading: { fontSize: FontSize.md, fontWeight: '900', color: Colors.textDark, marginTop: Spacing.md, marginBottom: Spacing.sm },
  dangerHeading: { fontSize: FontSize.lg, fontWeight: '900', color: Colors.textDark, marginBottom: Spacing.xs },
  twoColumn: { flexDirection: 'row' },
  helpText: { fontSize: FontSize.xs, color: Colors.gray, marginTop: -Spacing.sm, marginBottom: Spacing.sm },
  label: { fontSize: FontSize.sm, fontWeight: '900', color: Colors.textDark, marginBottom: 6 },
  filePickerRow: { flexDirection: 'row', alignItems: 'center', borderWidth: 1, borderColor: Colors.border, borderRadius: Radius.sm, backgroundColor: Colors.white, padding: 2 },
  fileButton: { backgroundColor: '#E5E7EB', paddingHorizontal: Spacing.sm, paddingVertical: 8, borderRadius: Radius.sm, marginRight: Spacing.sm },
  fileButtonText: { fontSize: FontSize.sm, color: Colors.textDark },
  fileText: { flex: 1, fontSize: FontSize.sm, color: Colors.gray },
});
